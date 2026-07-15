<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Certifier;
use App\Models\Country;
use App\Models\Product;
use App\Jobs\ProcessOUProductIntelligent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ScrapeAjdut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:ajdut {--limit= : Limit the number of products to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape products from Ajdut Kosher Argentina';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Ajdut Kosher Argentina scraping...');

        // Ensure Argentina country exists
        $country = Country::firstOrCreate(
            ['code' => 'AR'],
            ['name' => 'Argentina', 'slug' => 'argentina']
        );

        // Ensure Ajdut Kosher certifier exists
        $certifier = Certifier::firstOrCreate(
            ['slug' => 'ajdut-kosher'],
            [
                'name' => 'Ajdut Kosher',
                'full_name' => 'Ajdut Kosher - Asociación Religiosa',
                'description' => 'La certificadora más grande de Argentina y Sudamérica.',
                'website' => 'https://kosher.org.ar/',
            ]
        );

        // Attach country to certifier
        if (!$certifier->countries()->where('countries.id', $country->id)->exists()) {
            $certifier->countries()->attach($country->id);
        }

        $url = 'https://kosher.org.ar/api/products.php';
        $categoriasUrl = 'https://kosher.org.ar/api/categorias.php';

        $httpHeaders = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Referer' => 'https://kosher.org.ar/',
        ];

        // Ajdut publica su propio sitio armando la lista SOLO con los rubros que
        // aparecen en categorias.php. Los productos con un rubroId que no está ahí
        // (ej: listas de Pesaj del año que viene, todavía no liberadas) existen en
        // products.php pero Ajdut no los muestra públicamente. Replicamos ese mismo
        // filtro acá para no importar productos que la certificadora no publica.
        $this->info("Fetching valid rubros from {$categoriasUrl}...");
        $categoriasResponse = Http::withoutVerifying()->withHeaders($httpHeaders)->get($categoriasUrl);
        $validRubroIds = $categoriasResponse->successful()
            ? collect($categoriasResponse->json())->pluck('id')->map(fn ($id) => (string) $id)->all()
            : [];

        if (empty($validRubroIds)) {
            $this->warn('No se pudo obtener la lista de rubros válidos; se procesarán todos los productos sin filtrar por rubro.');
        }

        $this->info("Fetching products from {$url}...");

        try {
            $response = Http::withoutVerifying()
                ->withHeaders($httpHeaders)
                ->get($url);

            if (!$response->successful()) {
                $this->error("Failed to fetch products. Status: " . $response->status());
                return;
            }

            $products = $response->json();

            if (!empty($validRubroIds)) {
                $beforeCount = count($products);
                $products = array_values(array_filter($products, function ($item) use ($validRubroIds) {
                    return in_array((string) ($item['rubroId'] ?? ''), $validRubroIds, true);
                }));
                $skipped = $beforeCount - count($products);
                if ($skipped > 0) {
                    $this->info("Descartados {$skipped} productos con rubro no publicado por Ajdut (ej: listas futuras de Pesaj).");
                }
            }

            $total = count($products);
            $this->info("Found {$total} products.");

            $limit = $this->option('limit') ? (int)$this->option('limit') : $total;
            $count = 0;

            foreach ($products as $item) {
                if ($count >= $limit) break;

                try {
                    $name = trim($item['descripcion'] ?? '');
                    if (empty($name)) continue;
                    // Truncate name if too long
                    if (strlen($name) > 250) {
                        $name = substr($name, 0, 250) . '...';
                    }

                    // Clean Brand Name
                    $rawBrand = trim($item['marca'] ?? 'Unknown');
                    // Remove common suffixes like "- KITNIOT", "- SIN KITNIOT"
                    $brandName = preg_replace('/ - (SIN )?KITNIOT/i', '', $rawBrand);
                    $brandName = trim($brandName);
                    
                    if (empty($brandName)) $brandName = 'Unknown';

                    // Map Status
                    $rawStatus = mb_strtolower($item['lecheparve'] ?? '');
                    $kosherStatus = 'Unknown';
                    if (str_contains($rawStatus, 'lacteo') || str_contains($rawStatus, 'lácteo') || str_contains($rawStatus, 'leche')) {
                        $kosherStatus = 'Dairy';
                    } elseif (str_contains($rawStatus, 'parve') || str_contains($rawStatus, 'pareve')) {
                        $kosherStatus = 'Pareve';
                    } elseif (str_contains($rawStatus, 'carne') || str_contains($rawStatus, 'cárnico') || str_contains($rawStatus, 'basari')) {
                        $kosherStatus = 'Meat';
                    }

                    // Barcode cleaning
                    $barcode = trim($item['barcode'] ?? '');
                    if ($barcode === '.' || $barcode === '-' || $barcode === '-.' || empty($barcode)) {
                        $barcode = null;
                    }
                    // Truncate barcode if too long
                    if ($barcode && strlen($barcode) > 250) {
                        $barcode = substr($barcode, 0, 250);
                    }

                    // Create or update Brand
                    $brandSlug = Str::slug($brandName);
                    $brand = Brand::where('slug', $brandSlug)->first();
                    if (!$brand) {
                        $brand = Brand::firstOrCreate(
                            ['name' => $brandName],
                            ['slug' => $brandSlug]
                        );
                    }

                    // Image URL from AJDUT
                    $imageUrl = null;
                    if (!empty($item['imagen']) && $item['imagen'] !== '.') {
                         $imageUrl = 'https://kosher.org.ar/images/' . $item['imagen'];
                    }

                    // Description pública: solo datos reales de cara al usuario.
                    // El "rubro" es metadata interna de Ajdut (y a veces trae notas
                    // internas tipo "NOPUBLICAR"), así que no se muestra en el sitio.
                    $description = '';
                    if (!empty($item['sintacc']) && $item['sintacc'] === 'Si') {
                        $description = 'Sin gluten (sin TACC).';
                    }

                    // Disparar job inteligente para buscar barcode e imagen en OFF
                    ProcessOUProductIntelligent::dispatch([
                        'name' => $name,
                        'brand' => $brandName,
                        'status' => 'Ajdut ' . $kosherStatus,
                        'description' => trim($description),
                        'image_url' => $imageUrl, // Pasar imagen de AJDUT como fallback
                        'category' => $item['rubro'] ?? null,
                        'country' => 'AR',
                        'certifier_id' => $certifier->id,
                        'source' => 'ajdut_ar'
                    ])->onQueue('scraping');
                    
                    Log::info('Dispatched AJDUT intelligent matching job', [
                        'product_name' => $name,
                        'brand' => $brandName,
                        'status' => 'Ajdut ' . $kosherStatus,
                        'job_class' => 'ProcessOUProductIntelligent'
                    ]);

                    $count++;
                    if ($count % 50 === 0) {
                        $this->info("Processed {$count} products...");
                    }

                } catch (\Exception $e) {
                    $this->error("Error processing item {$item['id']}: " . $e->getMessage());
                }
            }

            $this->info("Scraping completed. Processed {$count} items.");

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
