<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SCRAPING DETALLADO - OBTENIENDO PRODUCTOS REALES ===\n\n";

// Configurar HTTP client para ignorar SSL y ser más permisivo
Http::macro('scrape', function () {
    return Http::withoutVerifying()
        ->timeout(60)
        ->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive'
        ]);
});

// 1. BDK Brasil - Intento agresivo
echo "1. Scrapeando BDK Brasil...\n";
try {
    $response = Http::scrape()->get('https://www.bdk.com.br/');
    if ($response->successful()) {
        $html = $response->body();
        echo "  Página cargada: " . strlen($html) . " bytes\n";
        
        // Buscar productos
        $patterns = [
            '/<a[^>]+href=[\'"]([^\'"]*produto[^\'"]*)[\'"][^>]*>([^<]+)<\/a>/i',
            '/<a[^>]+href=[\'"]([^\'"]*product[^\'"]*)[\'"][^>]*>([^<]+)<\/a>/i',
            '/<div[^>]*class=[\'"][^\'"]*produto[^\'"]*[\'"][^>]*>([^<]+)<\/div>/i',
            '/<div[^>]*class=[\'"][^\'"]*product[^\'"]*[\'"][^>]*>([^<]+)<\/div>/i'
        ];
        
        $foundProducts = [];
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $name = trim(strip_tags($match[2] ?? $match[1] ?? ''));
                    if (!empty($name) && strlen($name) > 2) {
                        $foundProducts[] = $name;
                    }
                }
            }
        }
        
        echo "  Productos encontrados: " . count($foundProducts) . "\n";
        foreach (array_slice($foundProducts, 0, 5) as $product) {
            echo "    - {$product}\n";
        }
        
        // Agregar productos reales si se encontraron
        if (!empty($foundProducts)) {
            $certifier = App\Models\Certifier::where('slug', 'bdk-brasil')->first();
            $brand = App\Models\Brand::firstOrCreate(['slug' => 'bdk-brasil'], [
                'name' => 'BDK Brasil',
                'description' => 'Marca certificada por BDK Brasil'
            ]);
            
            $added = 0;
            foreach (array_slice($foundProducts, 0, 10) as $productName) {
                $slug = Str::slug($productName . '-bdk-brasil');
                if (!App\Models\Product::where('slug', $slug)->exists()) {
                    App\Models\Product::create([
                        'name' => $productName,
                        'slug' => $slug,
                        'brand_id' => $brand->id,
                        'certifier_id' => $certifier->id,
                        'kosher_status' => 'Certificado',
                        'description' => "Producto kosher certificado por BDK Brasil",
                        'source' => 'bdk_scraper_real'
                    ]);
                    $added++;
                }
            }
            echo "  Productos agregados: {$added}\n";
        }
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Kosher Chile - Intento agresivo
echo "2. Scrapeando Kosher Chile...\n";
try {
    $response = Http::scrape()->get('https://www.chilekosher.cl/productos-kosher/');
    if ($response->successful()) {
        $html = $response->body();
        echo "  Página cargada: " . strlen($html) . " bytes\n";
        
        // Buscar enlaces de productos
        if (preg_match_all('/<a[^>]+href=[\'"]([^\'"]*producto[^\'"]*)[\'"][^>]*>([^<]+)<\/a>/i', $html, $matches, PREG_SET_ORDER)) {
            $foundProducts = [];
            foreach ($matches as $match) {
                $name = trim(strip_tags($match[2]));
                if (!empty($name) && strlen($name) > 2) {
                    $foundProducts[] = $name;
                }
            }
            
            echo "  Productos encontrados: " . count($foundProducts) . "\n";
            foreach (array_slice($foundProducts, 0, 5) as $product) {
                echo "    - {$product}\n";
            }
            
            // Agregar productos reales
            if (!empty($foundProducts)) {
                $certifier = App\Models\Certifier::where('slug', 'kosher-chile')->first();
                $brand = App\Models\Brand::firstOrCreate(['slug' => 'kosher-chile'], [
                    'name' => 'Chile Kosher',
                    'description' => 'Marca certificada por Chile Kosher'
                ]);
                
                $added = 0;
                foreach (array_slice($foundProducts, 0, 10) as $productName) {
                    $slug = Str::slug($productName . '-kosher-chile');
                    if (!App\Models\Product::where('slug', $slug)->exists()) {
                        App\Models\Product::create([
                            'name' => $productName,
                            'slug' => $slug,
                            'brand_id' => $brand->id,
                            'certifier_id' => $certifier->id,
                            'kosher_status' => 'Certificado',
                            'description' => "Producto kosher certificado por Chile Kosher",
                            'source' => 'kosher_chile_scraper_real'
                        ]);
                        $added++;
                    }
                }
                echo "  Productos agregados: {$added}\n";
            }
        }
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. UK Kosher - Intento agresivo  
echo "3. Scrapeando UK Kosher...\n";
try {
    $response = Http::scrape()->get('https://ukkosher.org/');
    if ($response->successful()) {
        $html = $response->body();
        echo "  Página cargada: " . strlen($html) . " bytes\n";
        
        // Buscar menciones de productos o marcas
        $patterns = [
            '/<a[^>]+href=[\'"]([^\'"]*)[\'"][^>]*>([^<]*(?:producto|marca|empresa)[^<]*)<\/a>/i',
            '/<h[1-6][^>]*>([^<]*(?:producto|marca|empresa)[^<]*)<\/h[1-6]>/i'
        ];
        
        $foundProducts = [];
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $name = trim(strip_tags($match[2]));
                    if (!empty($name) && strlen($name) > 2 && !str_contains(strtolower($name), 'qué es')) {
                        $foundProducts[] = $name;
                    }
                }
            }
        }
        
        echo "  Productos/marcas encontrados: " . count($foundProducts) . "\n";
        foreach (array_slice($foundProducts, 0, 3) as $product) {
            echo "    - {$product}\n";
        }
        
        // Agregar productos encontrados
        if (!empty($foundProducts)) {
            $certifier = App\Models\Certifier::where('slug', 'uk-kosher-latam')->first();
            $brand = App\Models\Brand::firstOrCreate(['slug' => 'uk-kosher-latam'], [
                'name' => 'UK Kosher Latinoamérica',
                'description' => 'Marca certificada por UK Kosher Latinoamérica'
            ]);
            
            $added = 0;
            foreach (array_slice($foundProducts, 0, 5) as $productName) {
                $slug = Str::slug($productName . '-uk-kosher');
                if (!App\Models\Product::where('slug', $slug)->exists()) {
                    App\Models\Product::create([
                        'name' => $productName,
                        'slug' => $slug,
                        'brand_id' => $brand->id,
                        'certifier_id' => $certifier->id,
                        'kosher_status' => 'Certificado',
                        'description' => "Producto kosher certificado por UK Kosher Latinoamérica",
                        'source' => 'uk_kosher_scraper_real'
                    ]);
                    $added++;
                }
            }
            echo "  Productos agregados: {$added}\n";
        }
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN FINAL ===\n";
$certifiers = [
    'bdk-brasil' => 'BDK Brasil',
    'kosher-chile' => 'Chile Kosher', 
    'uk-kosher-latam' => 'UK Kosher Latinoamérica',
    'kehila' => 'Kehila Uruguay'
];

foreach ($certifiers as $slug => $name) {
    $count = App\Models\Certifier::where('slug', $slug)->first()->products()->count();
    echo "- {$name}: {$count} productos\n";
}

echo "\n¡Scraping completado! Visita http://kosherstatus.test/certifiers\n";
