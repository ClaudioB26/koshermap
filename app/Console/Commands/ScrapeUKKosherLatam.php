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

class ScrapeUKKosherLatam extends Command
{
    protected $signature = 'scrape:uk-kosher {--limit=100}';
    protected $description = 'Scrape productos kosher de UK Kosher Latinoamérica';

    private $baseUrl = 'https://ukkosher.org/';
    private $certifierSlug = 'uk-kosher-latam';
    private $processed = 0;
    private $failed = 0;

    public function handle()
    {
        $this->info('=== SCRAPER UK KOSHER LATINOAMÉRICA ===');
        $this->info('Iniciando scraping de productos kosher...');

        try {
            // 1. Obtener o crear certificadora
            $certifier = $this->getOrCreateCertifier();

            // 2. Buscar productos en diferentes secciones
            $this->info('Buscando productos en diferentes secciones...');
            $productLinks = $this->getAllProductLinks();

            if (empty($productLinks)) {
                $this->error('No se encontraron enlaces de productos');
                return;
            }

            $this->info("Encontrados " . count($productLinks) . " productos para procesar");

            // 3. Procesar productos con límite
            $limit = $this->option('limit');
            $processedCount = 0;

            foreach ($productLinks as $index => $link) {
                if ($processedCount >= $limit) {
                    $this->info("Límite alcanzado: {$processedCount}/{$limit}");
                    break;
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

            // 4. Resumen final
            $this->printSummary();

        } catch (\Exception $e) {
            $this->error('Error general en scraping: ' . $e->getMessage());
            Log::error('UK Kosher scraping error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
                'name' => 'UK Kosher Latinoamérica',
                'slug' => $this->certifierSlug,
                'logo_symbol' => 'UK',
                'website' => 'https://ukkosher.org/',
                'description' => 'UK Kosher - Certificación kosher con alcance internacional y respaldo significativo en Latinoamérica'
            ]);
            
            $this->info("Certificadora '{$certifier->name}' creada");
        }

        return $certifier;
    }

    /**
     * Obtener todos los enlaces de productos de diferentes secciones
     */
    private function getAllProductLinks()
    {
        $allLinks = [];
        
        // Secciones a explorar
        $sections = [
            'consumidor' => 'https://ukkosher.org/consumidor/',
            'empresas' => 'https://ukkosher.org/empresas/',
            'marcas-destacadas' => 'https://ukkosher.org/empresas/marcas-destacadas/',
            'restaurantes-pizzerias' => 'https://ukkosher.org/category/expedicion-y-centros-de-comida/restaurantes-y-pizzerias/',
            'heladerias' => 'https://ukkosher.org/category/expedicion-y-centros-de-comida/heladerias/',
            'panaderias' => 'https://ukkosher.org/category/expedicion-y-centros-de-comida/panaderias/',
            'supermercados' => 'https://ukkosher.org/category/turismo/supermercados/',
            'noticias-alertas' => 'https://ukkosher.org/category/noticias-y-alertas/',
            'recetas-kosher' => 'https://ukkosher.org/category/recetas-kosher/'
        ];

        foreach ($sections as $sectionName => $sectionUrl) {
            $this->info("Explorando sección: {$sectionName}");
            
            $pageContent = $this->fetchPage($sectionUrl);
            
            if ($pageContent) {
                // Extraer enlaces de esta sección
                $sectionLinks = $this->extractProductLinksFromPage($pageContent, $sectionName);
                
                if (!empty($sectionLinks)) {
                    $allLinks = array_merge($allLinks, $sectionLinks);
                    $this->info("Encontrados " . count($sectionLinks) . " productos en {$sectionName}");
                }
            }
        }

        // Eliminar duplicados por URL
        $uniqueLinks = [];
        $seenUrls = [];
        
        foreach ($allLinks as $link) {
            if (!in_array($link['url'], $seenUrls)) {
                $uniqueLinks[] = $link;
                $seenUrls[] = $link['url'];
            }
        }

        return $uniqueLinks;
    }

    /**
     * Cargar página específica
     */
    private function fetchPage($url)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'es-AR,es;q=0.9,en;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br'
                ])
                ->get($url);

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
     * Extraer enlaces de productos de una página
     */
    private function extractProductLinksFromPage($html, $sectionName)
    {
        $productLinks = [];

        // Patrón para encontrar enlaces que puedan ser productos
        $pattern = '/<a[^>]+href=[\'"]([^\'"]+)[\'"][^>]*>([^<]+)<\/a>/i';

        if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches[1] as $index => $url) {
                $name = $matches[2][$index] ?? 'Producto ' . ($index + 1);
                
                // Limpiar el nombre del producto
                $name = html_entity_decode($name, ENT_QUOTES | ENT_HTML5);
                $name = strip_tags($name);
                $name = trim($name);
                
                // Filtrar enlaces relevantes (productos, marcas, empresas)
                if (!empty($name) && strlen($name) > 2 && $this->isRelevantLink($url, $name, $sectionName)) {
                    $productLinks[] = [
                        'url' => $url,
                        'name' => $name,
                        'source' => $sectionName
                    ];
                }
            }
        }

        return $productLinks;
    }

    /**
     * Verificar si un enlace es relevante para productos kosher
     */
    private function isRelevantLink($url, $name, $sectionName)
    {
        // Excluir enlaces no relevantes
        $excludePatterns = [
            'mailto:',
            'tel:',
            '#',
            'wp-content',
            'wp-admin',
            'feed',
            'xmlrpc',
            'login',
            'admin',
            'register',
            'comment',
            'reply',
            'edit',
            'pdf',
            'doc',
            'xls',
            'zip',
            'rar'
        ];

        foreach ($excludePatterns as $pattern) {
            if (str_contains($url, $pattern)) {
                return false;
            }
        }

        // Incluir si contiene palabras clave de productos
        $productKeywords = [
            'producto', 'marca', 'empresa', 'certificado', 'kosher', 
            'comida', 'alimento', 'bebida', 'dulce', 'pan', 'leche',
            'carne', 'pescado', 'fruta', 'verdura', 'aceite', 'azúcar',
            'sal', 'harina', 'chocolate', 'café', 'té', 'agua',
            'vino', 'cerveza', 'whisky', 'licor', 'snack', 'galleta'
        ];

        $nameLower = strtolower($name);
        foreach ($productKeywords as $keyword) {
            if (str_contains($nameLower, $keyword)) {
                return true;
            }
        }

        // Incluir si es de ciertas secciones específicas
        $relevantSections = [
            'marcas-destacadas',
            'restaurantes-pizzerias',
            'heladerias',
            'panaderias',
            'supermercados'
        ];

        if (in_array($sectionName, $relevantSections)) {
            return true;
        }

        // Excluir nombres que no parecen productos
        $excludeNames = [
            '¿qué es kosher?', 'cárnicos, lácteos y pareve', 'la diferencia de uk kosher',
            'normas a implemetar', 'la elección de una agencia kosher', 'pasos para obtener',
            'obtenga un presupuesto gratis', '¿para qué obtener la certificación kosher?',
            'etiqueta privada', 'confidencialidad y seguridad kosher', '¿cómo poder utilizarlo',
            'medicamentos pesaj', 'comer sano', 'recetas', 'noticias y alertas',
            'expedición y centros de comida', 'turismo', 'videos', 'entrada y salida del sol',
            'palabras de tora', 'calendario de fiestas judaicas', 'cataratas del iguazú',
            'sierras de córdoba', 'calafate', 'bariloche', 'mikvaot en argentina',
            'librerías judías', 'el cumplimiento kosher en la pascua'
        ];

        if (in_array(strtolower($name), $excludeNames)) {
            return false;
        }

        return false;
    }

    /**
     * Procesar un producto individual
     */
    private function processProduct($link, $certifier)
    {
        // Cargar página del producto
        $productHtml = $this->fetchPage($link['url']);
        
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
                'source' => 'uk_kosher_scraper',
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
            'source' => 'uk_kosher_scraper',
            'unique_hash' => md5($productData['name'] . $brand->id . $certifier->id)
        ]);

        $this->info("Producto '{$productData['name']}' creado exitosamente");
    }

    /**
     * Extraer datos del producto de la página HTML
     */
    private function extractProductData($html, $fallbackName)
    {
        $data = [
            'name' => $fallbackName,
            'brand' => 'UK Kosher',
            'kosher_status' => 'Certificado',
            'description' => 'Producto kosher certificado por UK Kosher Latinoamérica',
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
                'description' => 'Marca de productos kosher certificados por UK Kosher Latinoamérica'
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
        $this->info("RESUMEN DEL SCRAPPING UK KOSHER LATINOAMÉRICA");
        $this->info(str_repeat("=", 60));
        
        $this->info("Productos procesados: {$this->processed}");
        $this->info("Productos fallidos: {$this->failed}");
        $this->info("Tasa de éxito: " . round(($this->processed / ($this->processed + $this->failed)) * 100, 2) . "%");
        
        // Estadísticas de la base de datos
        $totalProducts = Product::where('certifier_id', 
            Certifier::where('slug', $this->certifierSlug)->value('id')
        )->count();
        
        $this->info("Total productos UK Kosher en BD: {$totalProducts}");
        
        $this->info(str_repeat("=", 60));
    }
}
