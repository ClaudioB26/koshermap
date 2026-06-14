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

        if ($existing) {
            $payload = [
                'description' => $description,
                'kosher_status' => $kosherStatus,
                'certifier_id' => $certifier->id,
                'source' => 'bdk_scraper',
                'is_active' => true,
            ];

            if (!$existing->image_url) {
                $imageUrl = $this->fetchProductImage($row['nombre']);
                if ($imageUrl) {
                    $payload['image_url'] = $imageUrl;
                    $this->imagesFound++;
                }
            }

            $existing->update($payload);
            $this->markProductSeen($existing);
            $this->updated++;
            return;
        }

        $imageUrl = $this->fetchProductImage($row['nombre']);
        if ($imageUrl) {
            $this->imagesFound++;
        }

        $product = Product::create([
            'name' => $row['nombre'],
            'slug' => $this->generateUniqueSlug($row['nombre'], $brand),
            'brand_id' => $brand->id,
            'certifier_id' => $certifier->id,
            'kosher_status' => $kosherStatus,
            'description' => $description,
            'image_url' => $imageUrl,
            'source' => 'bdk_scraper',
            'unique_hash' => $uniqueHash,
            'is_active' => true,
        ]);

        $this->markProductSeen($product);
        $this->created++;
    }

    /**
     * Buscar la imagen de un producto en bdk.com.br a partir de su nombre exacto.
     * Devuelve la URL de la imagen "real" (alta resolución) o null si no se encuentra.
     */
    private function fetchProductImage(string $name): ?string
    {
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
                return null;
            }

            $html = $response->body();
            $target = mb_strtoupper(trim($name));

            if (preg_match_all('/<h1>\s*<a href="([^"]+)"[^>]*>([^<]+)<\/a>\s*<\/h1>/is', $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $matchedName = mb_strtoupper(trim(html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5)));

                    if ($matchedName === $target) {
                        return $this->fetchImageFromProductPage($match[1]);
                    }
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Cargar la página de detalle de un producto y extraer la URL de su imagen.
     */
    private function fetchImageFromProductPage(string $url): ?string
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                ])
                ->get($url);

            if (!$response->successful()) {
                return null;
            }

            $html = $response->body();

            if (preg_match('/<img[^>]+src="([^"]*anexos[^"]*)"/i', $html, $match)) {
                $imageUrl = $match[1];

                if (Http::timeout(15)->head($imageUrl)->successful()) {
                    return $imageUrl;
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
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
