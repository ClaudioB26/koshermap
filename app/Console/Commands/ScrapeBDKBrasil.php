<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Certifier;
use App\Models\Category;
use App\Jobs\ProcessOUProductIntelligent;
use Illuminate\Support\Str;

class ScrapeBDKBrasil extends Command
{
    protected $signature = 'scrape:bdk {--limit=100}';
    protected $description = 'Scrape productos kosher de BDK Brasil';

    private $baseUrl = 'https://www.bdk.com.br/';
    private $certifierSlug = 'bdk-brasil';
    private $processed = 0;
    private $failed = 0;
    private $excludedProducts = [];

    public function handle()
    {
        $this->info('=== SCRAPER BDK BRASIL ===');
        $this->info('Iniciando scraping de productos kosher...');

        try {
            // 1. Obtener o crear certificadora
            $certifier = $this->getOrCreateCertifier();

            // 2. Cargar lista de productos excluidos
            $this->info('Cargando lista de productos excluidos...');
            $this->loadExcludedProducts();

            // 3. Cargar página principal
            $this->info('Cargando página principal de productos...');
            $mainPage = $this->fetchMainPage();

            if (!$mainPage) {
                $this->error('No se pudo cargar la página principal');
                return;
            }

            // 4. Extraer enlaces de productos
            $this->info('Buscando enlaces de productos...');
            $productLinks = $this->extractProductLinks($mainPage);

            if (empty($productLinks)) {
                $this->error('No se encontraron enlaces de productos');
                return;
            }

            $this->info("Encontrados " . count($productLinks) . " productos para procesar");

            // 5. Procesar productos con límite
            $limit = $this->option('limit');
            $processedCount = 0;

            foreach ($productLinks as $index => $link) {
                if ($processedCount >= $limit) {
                    $this->info("Límite alcanzado: {$processedCount}/{$limit}");
                    break;
                }

                // Verificar si el producto está en la lista de excluidos
                if ($this->isProductExcluded($link['name'])) {
                    $this->info("Producto '{$link['name']}' está en lista de excluidos, saltando...");
                    continue;
                }

                $this->info("Procesando producto " . ($index + 1) . "/" . count($productLinks) . ": {$link['name']}");
                
                try {
                    $this->processProduct($link, $certifier);
                    $processedCount++;
                    $this->processed++;

                    // Pequeña demora para no sobrecargar el servidor
                    if ($processedCount % 10 === 0) {
                        sleep(1);
                        $this->info("Procesados {$processedCount} productos...");
                    }

                } catch (\Exception $e) {
                    $this->error("Error procesando {$link['name']}: " . $e->getMessage());
                    $this->failed++;
                }
            }

            // 6. Resumen final
            $this->printSummary();

        } catch (\Exception $e) {
            $this->error('Error general en scraping: ' . $e->getMessage());
            Log::error('BDK scraping error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Cargar lista de productos excluidos desde las alertas
     */
    private function loadExcludedProducts()
    {
        try {
            $alertasPage = $this->fetchAlertasPage();
            
            if ($alertasPage) {
                // Extraer nombres de productos de las alertas
                $this->extractExcludedProductsFromAlertas($alertasPage);
            }

            $this->info("Productos excluidos cargados: " . count($this->excludedProducts));

        } catch (\Exception $e) {
            $this->error('Error cargando productos excluidos: ' . $e->getMessage());
        }
    }

    /**
     * Extraer productos excluidos de las alertas
     */
    private function extractExcludedProductsFromAlertas($html)
    {
        // Patrón para encontrar nombres de productos en alertas
        $pattern = '/Produto foi excluido:([A-Z\s&\-\s]+)[^)]/i';
        
        if (preg_match_all($pattern, $html, $matches)) {
            foreach ($matches[1] as $productName) {
                $productName = trim(strtoupper($productName));
                if (!empty($productName)) {
                    $this->excludedProducts[] = $productName;
                }
            }
        }
    }

    /**
     * Cargar página de alertas
     */
    private function fetchAlertasPage()
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br'
                ])
                ->get($this->baseUrl);

            if ($response->successful()) {
                return $response->body();
            }

            return null;

        } catch (\Exception $e) {
            $this->error('Error fetching alertas: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener o crear certificadora
     */
    private function getOrCreateCertifier()
    {
        $certifier = Certifier::where('slug', $this->certifierSlug)->first();
        
        if (!$certifier) {
            $certifier = Certifier::create([
                'name' => 'BDK Brasil',
                'slug' => $this->certifierSlug,
                'logo_symbol' => 'BDK',
                'website' => 'https://www.bdk.com.br/',
                'description' => 'Badatz Diniz & Co. - Certificação kosher para alimentos no Brasil'
            ]);
            
            $this->info("Certificadora '{$certifier->name}' creada");
        }

        return $certifier;
    }

    /**
     * Cargar página principal
     */
    private function fetchMainPage()
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br'
                ])
                ->get($this->baseUrl);

            if ($response->successful()) {
                return $response->body();
            }

            $this->error('Error HTTP: ' . $response->status());
            return null;

        } catch (\Exception $e) {
            $this->error('Error fetching página: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extraer enlaces de productos del HTML
     */
    private function extractProductLinks($html)
    {
        $productLinks = [];

        // Patrón para encontrar enlaces de productos
        $pattern = '/<a[^>]+href=[\'"]([^\'"]+)[\'"][^>]*>([^<]+)<\/a>/i';

        if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches[1] as $index => $url) {
                $name = $matches[2][$index] ?? 'Producto ' . ($index + 1);
                
                // Limpiar el nombre del producto
                $name = html_entity_decode($name, ENT_QUOTES | ENT_HTML5);
                $name = strip_tags($name);
                $name = trim($name);
                
                if (!empty($name) && strlen($name) > 3) {
                    $productLinks[] = [
                        'url' => $url,
                        'name' => $name,
                        'source' => 'main_page'
                    ];
                }
            }
        }

        $this->info("Extraídos " . count($productLinks) . " enlaces de productos");
        return array_slice($productLinks, 0, $this->option('limit'));
    }

    /**
     * Verificar si un producto está en la lista de excluidos
     */
    private function isProductExcluded($productName)
    {
        $productName = trim(strtoupper($productName));
        
        foreach ($this->excludedProducts as $excluded) {
            if (str_contains($productName, $excluded)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Procesar un producto individual
     */
    private function processProduct($link, $certifier)
    {
        // Cargar página del producto
        $productHtml = $this->fetchProductPage($link['url']);
        
        if (!$productHtml) {
            throw new \Exception("No se pudo cargar la página del producto");
        }

        // Extraer datos del producto
        $productData = $this->extractProductData($productHtml, $link['name']);

        if (empty($productData['name'])) {
            throw new \Exception("No se pudieron extraer datos del producto");
        }

        // Crear o actualizar marca
        $brand = $this->getOrCreateBrand($productData['brand']);

        // Generar slug único
        $slug = $this->generateUniqueSlug($productData['name'], $brand);

        // Verificar si el producto ya existe
        $existingProduct = Product::where('name', $productData['name'])
            ->where('brand_id', $brand->id)
            ->first();

        if ($existingProduct) {
            $this->info("Producto '{$productData['name']}' ya existe, actualizando...");
            $existingProduct->update([
                'certifier_id' => $certifier->id,
                'description' => $productData['description'],
                'image_url' => $productData['image_url'],
                'source' => 'bdk_scraper',
                'unique_hash' => md5($productData['name'] . $brand->id . $certifier->id)
            ]);
            return;
        }

        // Crear nuevo producto
        Product::create([
            'name' => $productData['name'],
            'slug' => $slug,
            'brand_id' => $brand->id,
            'certifier_id' => $certifier->id,
            'kosher_status' => $productData['kosher_status'] ?? 'Certificado',
            'description' => $productData['description'],
            'image_url' => $productData['image_url'],
            'source' => 'bdk_scraper',
            'unique_hash' => md5($productData['name'] . $brand->id . $certifier->id)
        ]);

        $this->info("Producto '{$productData['name']}' creado exitosamente");
    }

    /**
     * Cargar página individual de producto
     */
    private function fetchProductPage($url)
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br'
                ])
                ->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            return null;

        } catch (\Exception $e) {
            $this->error("Error cargando producto {$url}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extraer datos del producto de la página HTML
     */
    private function extractProductData($html, $fallbackName)
    {
        $data = [
            'name' => $fallbackName,
            'brand' => 'BDK',
            'kosher_status' => 'Certificado',
            'description' => 'Producto kosher certificado por BDK Brasil',
            'image_url' => null
        ];

        // Extraer nombre del producto (más específico)
        $namePattern = '/<h1[^>]*>([^<]+)<\/h1>/i';
        if (preg_match($namePattern, $html, $matches)) {
            $data['name'] = strip_tags($matches[1]);
            $data['name'] = html_entity_decode($data['name'], ENT_QUOTES | ENT_HTML5);
            $data['name'] = trim($data['name']);
        }

        // Extraer descripción
        $descPattern = '/<div[^>]*class=[\'"][^\'"]*product-description[^\'"]*[\'"][^>]*>([^<]+)<\/div>/i';
        if (preg_match($descPattern, $html, $matches)) {
            $data['description'] = strip_tags($matches[1]);
            $data['description'] = html_entity_decode($data['description'], ENT_QUOTES | ENT_HTML5);
            $data['description'] = trim($data['description']);
        }

        // Extraer imagen
        $imagePattern = '/<img[^>]*src=[\'"](https:[^\'"]+)[\'"][^>]*>/i';
        if (preg_match($imagePattern, $html, $matches)) {
            $data['image_url'] = $matches[1];
        }

        // Extraer marca si está disponible
        $brandPattern = '/<span[^>]*class=[\'"][^\'"]*brand[^\'"]*[\'"][^>]*>([^<]+)<\/span>/i';
        if (preg_match($brandPattern, $html, $matches)) {
            $data['brand'] = strip_tags($matches[1]);
            $data['brand'] = html_entity_decode($data['brand'], ENT_QUOTES | ENT_HTML5);
            $data['brand'] = trim($data['brand']);
        }

        // Extraer estado kosher
        $statusPattern = '/<span[^>]*class=[\'"][^\'"]*kosher-status[^\'"]*[\'"][^>]*>([^<]+)<\/span>/i';
        if (preg_match($statusPattern, $html, $matches)) {
            $data['kosher_status'] = strip_tags($matches[1]);
            $data['kosher_status'] = html_entity_decode($data['kosher_status'], ENT_QUOTES | ENT_HTML5);
            $data['kosher_status'] = trim($data['kosher_status']);
        }

        return $data;
    }

    /**
     * Obtener o crear marca
     */
    private function getOrCreateBrand($brandName)
    {
        $brand = Brand::where('slug', Str::slug($brandName))->first();
        
        if (!$brand) {
            $brand = Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
                'description' => 'Marca de productos kosher certificados por BDK Brasil'
            ]);
            
            $this->info("Marca '{$brandName}' creada");
        }

        return $brand;
    }

    /**
     * Generar slug único
     */
    private function generateUniqueSlug($productName, $brand)
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
    private function printSummary()
    {
        $this->info("\n" . str_repeat("=", 60));
        $this->info("RESUMEN DEL SCRAPPING BDK BRASIL");
        $this->info(str_repeat("=", 60));
        
        $this->info("Productos procesados: {$this->processed}");
        $this->info("Productos fallidos: {$this->failed}");
        $this->info("Productos excluidos: " . count($this->excludedProducts));
        $this->info("Tasa de éxito: " . round(($this->processed / ($this->processed + $this->failed)) * 100, 2) . "%");
        
        // Estadísticas de la base de datos
        $totalProducts = Product::where('certifier_id', 
            Certifier::where('slug', $this->certifierSlug)->value('id')
        )->count();
        
        $this->info("Total productos BDK en BD: {$totalProducts}");
        
        $this->info(str_repeat("=", 60));
    }
}
