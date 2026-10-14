<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\TracksProductActivity;
use App\Models\Brand;
use App\Models\Certifier;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * One Kosher International (México, certificado "1Kosher").
 *
 * El sitio onekosher.org (onekosher.com es un dominio en venta) tiene un
 * buscador propio que consulta una API JSON pública:
 *   GET /wp-json/onekosher/v1/productos?search=<texto>&page=<n>   (50 por página)
 * Buscar "1K" (prefijo de todos los folios) devuelve el catálogo completo.
 * Cada fila trae marca, nombre, empresa, estatus, vencimiento, folio y la URL del
 * certificado. El robots.txt del sitio no restringe esta ruta.
 */
class ScrapeOneKosher extends Command
{
    use TracksProductActivity;

    protected $signature = 'scrape:onekosher {--limit=0 : Máximo de productos a procesar (0 = todos)} {--pages=0 : Máximo de páginas a pedir (0 = todas)}';
    protected $description = 'Scrape de productos kosher certificados por One Kosher International (México)';

    private const API_URL = 'https://www.onekosher.org/wp-json/onekosher/v1/productos';
    private const CERTIFIER_SLUG = 'one-kosher';
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36';

    private int $created = 0;
    private int $updated = 0;
    private int $skipped = 0;
    private int $failed = 0;
    private int $processed = 0;

    public function handle(): int
    {
        $this->info('=== SCRAPER ONE KOSHER (México) ===');

        $country = Country::firstOrCreate(['code' => 'MX'], ['name' => 'México', 'slug' => 'mexico']);

        $certifier = Certifier::firstOrCreate(
            ['slug' => self::CERTIFIER_SLUG],
            [
                'name'        => 'One Kosher',
                'full_name'   => 'One Kosher International',
                'logo_symbol' => '1K',
                'website'     => 'https://www.onekosher.org/',
            ]
        );
        if (!$certifier->countries()->where('countries.id', $country->id)->exists()) {
            $certifier->countries()->attach($country->id);
        }

        $limit    = (int) $this->option('limit');
        $maxPages = (int) $this->option('pages');
        $seenFolios = [];
        $page = 1;
        $lastPage = 0;
        $total = 0;
        $complete = true;

        while (true) {
            if ($maxPages > 0 && $page > $maxPages) {
                $complete = false;
                break;
            }

            $data = $this->fetchPage($page);
            if ($data === null) {
                // Error de red/servidor: no desactivar nada si el catálogo quedó incompleto
                $complete = false;
                break;
            }

            $rows = $data['resultados'] ?? [];

            if ($page === 1) {
                $total = (int) ($data['total'] ?? 0);
                $perPage = max(1, (int) ($data['por_pagina'] ?? 50));
                $lastPage = (int) ceil($total / $perPage);
                $this->info("Productos en el catálogo: {$total} ({$lastPage} páginas)");
            }

            // La API oculta algunas filas por página, así que una página puede venir
            // corta o vacía sin que sea la última: se recorre hasta la última calculada.
            if ($lastPage === 0 || $page > $lastPage) {
                break;
            }

            foreach ($rows as $row) {
                if ($limit > 0 && $this->processed >= $limit) {
                    $complete = false;
                    break 2;
                }

                try {
                    $folio = $this->processProduct($row, $certifier, $country);
                    if ($folio) {
                        $seenFolios[$folio] = true;
                    }
                    $this->processed++;
                } catch (\Throwable $e) {
                    $this->error("Error procesando '" . ($row['nombre'] ?? '?') . "': " . $e->getMessage());
                    $this->failed++;
                }
            }

            $this->line("  página {$page}: " . count($rows) . " filas (acumulado {$this->processed})");
            $page++;
            usleep(400000); // 0,4 s entre páginas: no cargar el sitio
        }

        // Protección: si la API devolvió mucho menos que el total anunciado, no se despublica nada.
        if ($complete && $this->processed < (int) ($total ?? 0) * 0.5) {
            $complete = false;
        }

        if ($complete) {
            $deactivated = $this->deactivateStaleProducts($certifier->id);
            $this->info("Productos desactivados (ya no están en el catálogo): {$deactivated}");
        } else {
            $this->warn('Desactivación de productos obsoletos omitida (catálogo incompleto o --limit/--pages).');
        }

        $this->printSummary($certifier);

        return $this->failed > 0 && $this->processed === 0 ? 1 : 0;
    }

    /**
     * Una página del catálogo. Reintenta 3 veces; null si no se pudo.
     */
    private function fetchPage(int $page): ?array
    {
        for ($try = 1; $try <= 3; $try++) {
            try {
                $response = Http::timeout(45)
                    ->withHeaders(['User-Agent' => self::USER_AGENT, 'Accept' => 'application/json'])
                    ->get(self::API_URL, ['search' => '1K', 'page' => $page]);

                if ($response->successful() && is_array($response->json())) {
                    return $response->json();
                }

                $this->warn("  HTTP {$response->status()} en la página {$page} (intento {$try})");
            } catch (\Throwable $e) {
                $this->warn("  Error en la página {$page} (intento {$try}): " . $e->getMessage());
            }

            sleep(2 * $try);
        }

        Log::error("One Kosher: no se pudo bajar la página {$page}");
        return null;
    }

    private function processProduct(array $row, Certifier $certifier, Country $country): ?string
    {
        $name    = trim((string) ($row['nombre'] ?? ''));
        $company = trim((string) ($row['empresa'] ?? ''));
        $brandNm = trim((string) ($row['marca'] ?? ''));
        $folio   = trim((string) ($row['folio'] ?? ''));

        if ($name === '') {
            $this->skipped++;
            return null;
        }

        $brandName = $brandNm !== '' ? $brandNm : ($company !== '' ? $company : 'One Kosher');
        $brand = $this->getOrCreateBrand($brandName);

        $status = $this->mapKosherStatus((string) ($row['estatus'] ?? ''));

        $parts = [];
        if ($company !== '') {
            $parts[] = "Empresa: {$company}.";
        }
        if (!empty($row['vence'])) {
            $parts[] = "Certificado vigente hasta {$row['vence']}.";
        }
        if ($folio !== '') {
            $parts[] = "Folio {$folio}.";
        }
        $description = implode(' ', $parts);

        // El folio es único por producto/certificado; si faltara, se cae al nombre + marca
        $uniqueHash = md5('onekosher_mx_' . ($folio !== '' ? $folio : $name . '_' . $brandName));

        $product = Product::where('unique_hash', $uniqueHash)->first();

        if ($product) {
            $product->update([
                'name'          => $name,
                'brand_id'      => $brand->id,
                'certifier_id'  => $certifier->id,
                'kosher_status' => $status,
                'description'   => $description,
                'source'        => 'onekosher_mx',
                'is_active'     => true,
            ]);
            $this->updated++;
        } else {
            $product = Product::create([
                'name'          => $name,
                'slug'          => $this->generateUniqueSlug($name, $brand),
                'brand_id'      => $brand->id,
                'certifier_id'  => $certifier->id,
                'kosher_status' => $status,
                'description'   => $description,
                'source'        => 'onekosher_mx',
                'unique_hash'   => $uniqueHash,
                'is_active'     => true,
            ]);
            $this->created++;
        }

        $this->markProductSeen($product);

        if (!$product->countries()->where('countries.id', $country->id)->exists()) {
            $product->countries()->attach($country->id);
        }

        return $folio !== '' ? $folio : $uniqueHash;
    }

    private function mapKosherStatus(string $estatus): string
    {
        $s = mb_strtolower(trim($estatus));

        return match (true) {
            str_contains($s, 'pareve') || str_contains($s, 'parve') => 'Pareve',
            str_contains($s, 'dairy') || str_contains($s, 'lacteo') || str_contains($s, 'lácteo') => 'Dairy',
            str_contains($s, 'meat') || str_contains($s, 'carne') => 'Meat',
            str_contains($s, 'fish') || str_contains($s, 'pescado') => 'OU Fish',
            default => $estatus !== '' ? $estatus : 'Unknown',
        };
    }

    private function getOrCreateBrand(string $brandName): Brand
    {
        $slug = Str::slug($brandName) ?: 'one-kosher';

        return Brand::firstOrCreate(
            ['slug' => $slug],
            ['name' => $brandName, 'description' => 'Marca de productos kosher certificados por One Kosher (México)']
        );
    }

    private function generateUniqueSlug(string $productName, Brand $brand): string
    {
        $base = Str::slug($productName . '-' . $brand->slug) ?: 'producto';
        $slug = $base;
        $i = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function printSummary(Certifier $certifier): void
    {
        $this->newLine();
        $this->info(str_repeat('=', 50));
        $this->info('RESUMEN ONE KOSHER');
        $this->info("Creados: {$this->created} | Actualizados: {$this->updated} | Omitidos: {$this->skipped} | Errores: {$this->failed}");
        $this->info('Total One Kosher en BD: ' . Product::where('certifier_id', $certifier->id)->count());
        $this->info(str_repeat('=', 50));
    }
}
