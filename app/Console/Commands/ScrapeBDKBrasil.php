<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Certifier;
use App\Console\Commands\Concerns\TracksProductActivity;
use Illuminate\Support\Str;
use ZipArchive;

class ScrapeBDKBrasil extends Command
{
    use TracksProductActivity;

    protected $signature = 'scrape:bdk {--limit=0 : Límite de productos a procesar (0 = sin límite)}';
    protected $description = 'Scrape de productos kosher certificados por BDK Brasil (vía exportación Excel del catálogo)';

    private const EXPORT_URL = 'https://bdk.com.br/produtos/gerar_excel/?produto=&supervisao-bdk=&fabricante=&categoria=&tipo=&sort=&order=';
    private const CERTIFIER_SLUG = 'bdk-brasil';

    private $processed = 0;
    private $created = 0;
    private $updated = 0;
    private $skipped = 0;
    private $failed = 0;
    private $imagesFound = 0;

    public function handle()
    {
        $this->info('=== SCRAPER BDK BRASIL ===');

        try {
            $certifier = $this->getOrCreateCertifier();

            $this->info('Descargando catálogo completo (Excel)...');
            $rows = $this->fetchCatalog();

            if ($rows === null) {
                $this->error('No se pudo descargar o leer el catálogo de BDK Brasil');
                return;
            }

            $this->info('Productos en el catálogo: ' . count($rows));

            $limit = (int) $this->option('limit');

            foreach ($rows as $row) {
                if ($limit > 0 && $this->processed >= $limit) {
                    $this->info("Límite alcanzado: {$limit}");
                    break;
                }

                // Solo importar productos actualmente certificados por BDK
                if (mb_strtolower($row['certificado']) !== 'sim') {
                    $this->skipped++;
                    continue;
                }

                try {
                    $this->processProduct($row, $certifier);
                    $this->processed++;

                    if ($this->processed % 20 === 0) {
                        $this->info("Procesados {$this->processed} productos...");
                        usleep(300000);
                    }
                } catch (\Exception $e) {
                    $this->error("Error procesando '{$row['nombre']}': " . $e->getMessage());
                    $this->failed++;
                }
            }

            if ($limit === 0) {
                $deactivated = $this->deactivateStaleProducts($certifier->id);
                $this->info("Productos desactivados (ya no están en el catálogo): {$deactivated}");
            } else {
                $this->warn('Desactivación de productos obsoletos omitida (uso de --limit).');
            }

            $this->printSummary();
        } catch (\Exception $e) {
            $this->error('Error general en scraping: ' . $e->getMessage());
            Log::error('BDK scraping error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Obtener o crear certificadora
     */
    private function getOrCreateCertifier(): Certifier
    {
        $certifier = Certifier::where('slug', self::CERTIFIER_SLUG)->first();

        if (!$certifier) {
            $certifier = Certifier::create([
                'name' => 'BDK Brasil',
                'slug' => self::CERTIFIER_SLUG,
                'logo_symbol' => 'BDK',
            ]);

            $this->info("Certificadora '{$certifier->name}' creada");
        }

        return $certifier;
    }

    /**
     * Descargar y parsear el catálogo de productos (XLSX)
     */
    private function fetchCatalog(): ?array
    {
        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                ])
                ->get(self::EXPORT_URL);

            if (!$response->successful()) {
                $this->error('Error HTTP: ' . $response->status());
                return null;
            }

            return $this->parseExcel($response->body());
        } catch (\Exception $e) {
            $this->error('Error descargando catálogo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Parsear el XLSX generado por bdk.com.br/produtos/gerar_excel
     *
     * Columnas: A=PRODUTO, B=DESCRIÇÃO, C=TIPO, D=CLASSIFICAÇÃO,
     *           E=CERTIFICADO BDK, F=MARCA, G=FABRICANTE, H=LISTA, I=ATENÇÃO
     */
    private function parseExcel(string $contents): ?array
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'bdk_') . '.xlsx';
        file_put_contents($tmpFile, $contents);

        try {
            $zip = new ZipArchive();
            if ($zip->open($tmpFile) !== true) {
                return null;
            }

            $sharedStrings = $this->readSharedStrings($zip);
            $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
            $zip->close();

            if ($sheetXml === false) {
                return null;
            }

            $sheet = simplexml_load_string($sheetXml);
            $rows = [];

            foreach ($sheet->sheetData->row as $i => $row) {
                if ($i === 0) {
                    continue; // fila de encabezados
                }

                $cells = [];
                foreach ($row->c as $c) {
                    $col = preg_replace('/[0-9]/', '', (string) $c['r']);
                    $value = (string) $c->v;
                    $cells[$col] = ((string) $c['t']) === 's'
                        ? ($sharedStrings[(int) $value] ?? '')
                        : $value;
                }

                $nombre = trim($cells['A'] ?? '');
                if ($nombre === '') {
                    continue;
                }

                $rows[] = [
                    'nombre'        => $nombre,
                    'descripcion'   => trim($cells['B'] ?? ''),
                    'categoria'     => trim($cells['C'] ?? ''),
                    'clasificacion' => trim($cells['D'] ?? ''),
                    'certificado'   => mb_strtolower(trim($cells['E'] ?? '')),
                    'marca'         => trim($cells['F'] ?? ''),
                    'fabricante'    => trim($cells['G'] ?? ''),
                    'lista'         => trim($cells['H'] ?? ''),
                    'atencion'      => trim($cells['I'] ?? ''),
                ];
            }

            return $rows;
        } finally {
            @unlink($tmpFile);
        }
    }

    /**
     * Leer la tabla de strings compartidos del XLSX
     */
    private function readSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml === false) {
            return [];
        }

        $sst = simplexml_load_string($xml);
        $strings = [];

        foreach ($sst->si as $si) {
            if (isset($si->t)) {
                $strings[] = (string) $si->t;
            } else {
                $text = '';
                foreach ($si->r as $r) {
                    $text .= (string) $r->t;
                }
                $strings[] = $text;
            }
        }

        return $strings;
    }

    /**
     * Procesar un producto individual
     */
    private function processProduct(array $row, Certifier $certifier): void
    {
        $brandName = $row['marca'] !== '' ? $row['marca'] : ($row['fabricante'] !== '' ? $row['fabricante'] : 'BDK');
        $brand = $this->getOrCreateBrand($brandName);

        $description = $row['descripcion'];
        if ($row['atencion'] !== '') {
            $description = trim($description . '. Atención: ' . $row['atencion'], '. ');
        }

        $kosherStatus = $this->mapKosherStatus($row['clasificacion']);
        $uniqueHash = md5('bdk_brasil_' . $row['nombre'] . '_' . $brandName);

        $existing = Product::where('unique_hash', $uniqueHash)->first();

        $meta = $this->fetchBdkProductMeta($row['nombre'], $brandName);

        if ($meta['obs_importante'] && !str_contains($description, $meta['obs_importante'])) {
            $description = trim($description . '. Atención: ' . $meta['obs_importante'], '. ');
        }

        if ($existing) {
            $payload = [
                'description' => $description,
                'kosher_status' => $kosherStatus,
                'certifier_id' => $certifier->id,
                'source' => 'bdk_scraper',
                'is_active' => true,
            ];

            if (!$existing->image_url && $meta['image_url']) {
                $payload['image_url'] = $meta['image_url'];
                $this->imagesFound++;
            }

            $existing->update($payload);
            $this->markProductSeen($existing);
            $this->updated++;
            return;
        }

        if ($meta['image_url']) {
            $this->imagesFound++;
        }

        $product = Product::create([
            'name' => $row['nombre'],
            'slug' => $this->generateUniqueSlug($row['nombre'], $brand),
            'brand_id' => $brand->id,
            'certifier_id' => $certifier->id,
            'kosher_status' => $kosherStatus,
            'description' => $description,
            'image_url' => $meta['image_url'],
            'source' => 'bdk_scraper',
            'unique_hash' => $uniqueHash,
            'is_active' => true,
        ]);

        $this->markProductSeen($product);
        $this->created++;
    }

    /**
     * Buscar metadatos de un producto en bdk.com.br a partir de su nombre exacto,
     * usando los bloques var_dump (comentarios HTML de debug) de la página de búsqueda.
     * Si hay varios productos con el mismo nombre (distintos fabricantes), se elige
     * el que mejor coincida con la marca/fabricante de nuestro producto.
     * Devuelve ['image_url' => ?string, 'obs_importante' => ?string].
     */
    private function fetchBdkProductMeta(string $name, string $brandName): array
    {
        $result = ['image_url' => null, 'obs_importante' => null];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                ])
                ->get('https://bdk.com.br/produtos', [
                    'produto' => $name,
                    'fabricante' => '',
                    'categoria' => '',
                    'tipo' => '',
                    'pesquisar' => 'Pesquisar',
                ]);

            if (!$response->successful()) {
                return $result;
            }

            $html = $response->body();
            $target = mb_strtoupper(trim($name));

            $blocks = preg_split('/<!--\s*object\(stdClass\)#\d+/', $html);
            $candidates = [];

            foreach ($blocks as $block) {
                if (!preg_match('/\["produto"\]=>\s*\n\s*string\(\d+\) "([^\n]*)"/', $block, $m)) {
                    continue;
                }

                $produtoName = mb_strtoupper(trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5)));

                if ($produtoName !== $target) {
                    continue;
                }

                $candidates[] = [
                    'id_produto' => $this->extractBdkIntField($block, 'id_produto'),
                    'arquivo' => $this->extractBdkStringField($block, 'arquivo'),
                    'marca' => $this->extractBdkStringField($block, 'marca') ?? '',
                    'fabricante' => $this->extractBdkStringField($block, 'fabricante') ?? '',
                    'obs_importante' => $this->extractBdkStringField($block, 'obs_importante'),
                ];
            }

            if (empty($candidates)) {
                return $result;
            }

            $best = $this->pickBestBdkCandidate($candidates, $brandName);

            if ($best['id_produto'] && $best['arquivo']) {
                foreach (['reais', 'thumbs'] as $variant) {
                    $imageUrl = "https://www.bdk.com.br/anexos/produtos/{$best['id_produto']}/{$variant}/{$best['arquivo']}";

                    if (Http::timeout(15)->head($imageUrl)->successful()) {
                        $result['image_url'] = $imageUrl;
                        break;
                    }
                }
            }

            $obsImportante = $best['obs_importante'];

            // Si el candidato elegido no tiene "obs_importante" propio, buscar entre los
            // demás candidatos del mismo fabricante: la observación suele aplicar a todos
            // los productos homónimos fabricados por esa misma empresa.
            if (!$obsImportante && $best['fabricante']) {
                foreach ($candidates as $candidate) {
                    if ($candidate['obs_importante']
                        && mb_strtoupper(trim($candidate['fabricante'])) === mb_strtoupper(trim($best['fabricante']))
                    ) {
                        $obsImportante = $candidate['obs_importante'];
                        break;
                    }
                }
            }

            if ($obsImportante) {
                $result['obs_importante'] = trim(html_entity_decode($obsImportante, ENT_QUOTES | ENT_HTML5));
            }

            return $result;
        } catch (\Exception $e) {
            return $result;
        }
    }

    /**
     * Elegir el candidato cuya marca/fabricante mejor coincida con la marca de nuestro producto.
     */
    private function pickBestBdkCandidate(array $candidates, string $brandName): array
    {
        if (count($candidates) === 1) {
            return $candidates[0];
        }

        $ourWords = $this->normalizeBdkWords($brandName);
        $best = $candidates[0];
        $bestScore = -1;

        foreach ($candidates as $candidate) {
            $bdkWords = array_merge(
                $this->normalizeBdkWords($candidate['marca']),
                $this->normalizeBdkWords($candidate['fabricante'])
            );

            $score = count(array_intersect($ourWords, $bdkWords));

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $candidate;
            }
        }

        return $best;
    }

    /**
     * Normalizar texto a un conjunto de palabras en mayúsculas sin acentos, para comparación.
     */
    private function normalizeBdkWords(string $text): array
    {
        $text = mb_strtoupper($text);
        $text = strtr($text, [
            'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C', 'Ñ' => 'N',
        ]);

        return array_values(array_filter(preg_split('/[^A-Z0-9]+/', $text)));
    }

    /**
     * Extraer un campo string("...") de un bloque var_dump.
     */
    private function extractBdkStringField(string $block, string $key): ?string
    {
        if (preg_match('/\["' . preg_quote($key, '/') . '"\]=>\s*\n\s*string\(\d+\) "([^\n]*)"/', $block, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * Extraer un campo int(...) de un bloque var_dump.
     */
    private function extractBdkIntField(string $block, string $key): ?int
    {
        if (preg_match('/\["' . preg_quote($key, '/') . '"\]=>\s*\n\s*int\((\d+)\)/', $block, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    /**
     * Mapear la clasificación de BDK al estatus kosher interno
     */
    private function mapKosherStatus(string $clasificacion): string
    {
        $c = mb_strtolower($clasificacion);

        if (str_contains($c, 'chalav')) {
            return 'Dairy';
        }

        if (str_contains($c, 'carne') || str_contains($c, 'basari')) {
            return 'Meat';
        }

        if (str_contains($c, 'parve')) {
            return 'Pareve';
        }

        return 'Unknown';
    }

    /**
     * Obtener o crear marca
     */
    private function getOrCreateBrand(string $brandName): Brand
    {
        $slug = Str::slug($brandName);
        $brand = Brand::where('slug', $slug)->first();

        if (!$brand) {
            $brand = Brand::create([
                'name' => $brandName,
                'slug' => $slug,
                'description' => 'Marca de productos kosher certificados por BDK Brasil',
            ]);

            $this->info("Marca '{$brandName}' creada");
        }

        return $brand;
    }

    /**
     * Generar slug único
     */
    private function generateUniqueSlug(string $productName, Brand $brand): string
    {
        $baseSlug = Str::slug($productName . '-' . $brand->slug);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Imprimir resumen del proceso
     */
    private function printSummary(): void
    {
        $this->info("\n" . str_repeat('=', 60));
        $this->info('RESUMEN DEL SCRAPING BDK BRASIL');
        $this->info(str_repeat('=', 60));

        $this->info("Productos creados: {$this->created}");
        $this->info("Productos actualizados: {$this->updated}");
        $this->info("Imágenes encontradas: {$this->imagesFound}");
        $this->info("Productos no certificados (omitidos): {$this->skipped}");
        $this->info("Errores: {$this->failed}");

        $totalProducts = Product::where('certifier_id',
            Certifier::where('slug', self::CERTIFIER_SLUG)->value('id')
        )->count();

        $this->info("Total productos BDK en BD: {$totalProducts}");
        $this->info(str_repeat('=', 60));
    }
}
