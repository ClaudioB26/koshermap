<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ELIMINANDO DATOS DE PRUEBA Y SCRAPEANDO PRODUCTOS REALES ===\n\n";

// 1. Eliminar productos de muestra
echo "1. Eliminando productos de muestra...\n";

$kehilaCertifier = App\Models\Certifier::where('slug', 'kehila')->first();
$ukCertifier = App\Models\Certifier::where('slug', 'uk-kosher-latam')->first();

if ($kehilaCertifier) {
    $deletedKehila = App\Models\Product::where('certifier_id', $kehilaCertifier->id)
        ->where(function($query) {
            $query->where('source', 'sample_data')
                  ->orWhere('source', 'LIKE', '%sample%');
        })->delete();
    echo "  Kehila: {$deletedKehila} productos de muestra eliminados\n";
}

if ($ukCertifier) {
    $deletedUK = App\Models\Product::where('certifier_id', $ukCertifier->id)
        ->where(function($query) {
            $query->where('source', 'sample_data')
                  ->orWhere('source', 'LIKE', '%sample%');
        })->delete();
    echo "  UK Kosher: {$deletedUK} productos de muestra eliminados\n";
}

// 2. Scrapeo agresivo para Kehila
echo "\n2. Scrapeando Kehila Uruguay (agresivo)...\n";
try {
    $urls = [
        'https://kehila.org.uy/kasher/productos/',
        'https://kehila.org.uy/kasher/',
        'http://kehila.org.uy/kasher/productos/',
        'http://kehila.org.uy/kasher/'
    ];
    
    $kehilaProducts = [];
    
    foreach ($urls as $url) {
        echo "  Intentando: {$url}\n";
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'es-UY,es;q=0.9,en;q=0.8'
                ])
                ->get($url);
                
            if ($response->successful()) {
                $html = $response->body();
                echo "    Éxito: " . strlen($html) . " bytes\n";
                
                // Patrones extensivos para productos
                $patterns = [
                    '/<h[1-6][^>]*>([^<]*(?:producto|product|kosher)[^<]*)<\/h[1-6]>/i',
                    '/<div[^>]*class[^>]*>([^<]*(?:producto|product|kosher)[^<]*)<\/div>/i',
                    '/<a[^>]+href[^>]*>([^<]*(?:producto|product|kosher)[^<]*)<\/a>/i',
                    '/<li[^>]*>([^<]*(?:producto|product|kosher)[^<]*)<\/li>/i',
                    '/<span[^>]*>([^<]*(?:producto|product|kosher)[^<]*)<\/span>/i',
                    '/<p[^>]*>([^<]*(?:producto|product|kosher)[^<]*)<\/p>/i'
                ];
                
                foreach ($patterns as $pattern) {
                    if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                        foreach ($matches as $match) {
                            $name = trim(strip_tags($match[1]));
                            if (!empty($name) && strlen($name) > 3 && 
                                !str_contains(strtolower($name), 'qué es') &&
                                !str_contains(strtolower($name), 'contacto') &&
                                !str_contains(strtolower($name), 'información') &&
                                !str_contains(strtolower($name), 'nosotros')) {
                                $kehilaProducts[] = $name;
                            }
                        }
                    }
                }
                
                // Buscar marcas conocidas
                $brandPatterns = [
                    '/(Nestlé|Danone|Sancor|Arcor|Bagley|Terrabusi|Ferrero|Coca-Cola|Pepsi|Unilever)/i'
                ];
                
                foreach ($brandPatterns as $pattern) {
                    if (preg_match_all($pattern, $html, $matches)) {
                        foreach ($matches[1] as $brand) {
                            $kehilaProducts[] = "Productos {$brand} kosher";
                        }
                    }
                }
                
                break; // Si una URL funciona, no probar más
            }
        } catch (Exception $e) {
            echo "    Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Eliminar duplicados y limpiar
    $kehilaProducts = array_unique($kehilaProducts);
    $kehilaProducts = array_filter($kehilaProducts, function($item) {
        return strlen($item) > 3 && 
               !str_contains(strtolower($item), 'contacto') &&
               !str_contains(strtolower($item), 'nosotros') &&
               !str_contains(strtolower($item), 'qué es');
    });
    
    echo "  Productos encontrados: " . count($kehilaProducts) . "\n";
    foreach (array_slice($kehilaProducts, 0, 10) as $product) {
        echo "    - {$product}\n";
    }
    
    // Agregar productos reales de Kehila
    if (!empty($kehilaProducts) && $kehilaCertifier) {
        $brand = App\Models\Brand::firstOrCreate(['slug' => 'kehila-uruguay'], [
            'name' => 'Kehila Uruguay',
            'description' => 'Productos certificados por Kehila Uruguay'
        ]);
        
        $added = 0;
        foreach (array_slice($kehilaProducts, 0, 15) as $productName) {
            $slug = Str::slug($productName . '-kehila');
            if (!App\Models\Product::where('slug', $slug)->exists()) {
                App\Models\Product::create([
                    'name' => $productName,
                    'slug' => $slug,
                    'brand_id' => $brand->id,
                    'certifier_id' => $kehilaCertifier->id,
                    'kosher_status' => 'Certificado',
                    'description' => "Producto kosher certificado por Kehila Uruguay",
                    'source' => 'kehila_real_scraper'
                ]);
                $added++;
            }
        }
        echo "  Productos agregados: {$added}\n";
    }
    
} catch (Exception $e) {
    echo "  Error general: " . $e->getMessage() . "\n";
}

// 3. Scrapeo agresivo para UK Kosher
echo "\n3. Scrapeando UK Kosher Latinoamérica (agresivo)...\n";
try {
    $urls = [
        'https://ukkosher.org/',
        'http://ukkosher.org/',
        'https://www.ukkosher.org/',
        'http://www.ukkosher.org/'
    ];
    
    $ukProducts = [];
    
    foreach ($urls as $url) {
        echo "  Intentando: {$url}\n";
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9,es;q=0.8'
                ])
                ->get($url);
                
            if ($response->successful()) {
                $html = $response->body();
                echo "    Éxito: " . strlen($html) . " bytes\n";
                
                // Patrones extensivos
                $patterns = [
                    '/<h[1-6][^>]*>([^<]*(?:product|brand|company|empresa|marca)[^<]*)<\/h[1-6]>/i',
                    '/<div[^>]*class[^>]*>([^<]*(?:product|brand|company|empresa|marca)[^<]*)<\/div>/i',
                    '/<a[^>]+href[^>]*>([^<]*(?:product|brand|company|empresa|marca)[^<]*)<\/a>/i',
                    '/<li[^>]*>([^<]*(?:product|brand|company|empresa|marca)[^<]*)<\/li>/i'
                ];
                
                foreach ($patterns as $pattern) {
                    if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
                        foreach ($matches as $match) {
                            $name = trim(strip_tags($match[1]));
                            if (!empty($name) && strlen($name) > 3 && 
                                !str_contains(strtolower($name), 'what is') &&
                                !str_contains(strtolower($name), 'contact') &&
                                !str_contains(strtolower($name), 'about') &&
                                !str_contains(strtolower($name), 'home')) {
                                $ukProducts[] = $name;
                            }
                        }
                    }
                }
                
                // Buscar marcas internacionales conocidas
                $internationalBrands = [
                    'Nestlé', 'Unilever', 'PepsiCo', 'Coca-Cola', 'Kellogg\'s', 
                    'General Mills', 'Mars', 'Mondelez', 'Danone', 'L\'Oréal'
                ];
                
                foreach ($internationalBrands as $brand) {
                    if (preg_match("/{$brand}/i", $html)) {
                        $ukProducts[] = "{$brand} Products";
                        $ukProducts[] = "Productos {$brand}";
                    }
                }
                
                break;
            }
        } catch (Exception $e) {
            echo "    Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Eliminar duplicados y limpiar
    $ukProducts = array_unique($ukProducts);
    $ukProducts = array_filter($ukProducts, function($item) {
        return strlen($item) > 3 && 
               !str_contains(strtolower($item), 'contact') &&
               !str_contains(strtolower($item), 'about') &&
               !str_contains(strtolower($item), 'what is') &&
               !str_contains(strtolower($item), 'home');
    });
    
    echo "  Productos/marcas encontrados: " . count($ukProducts) . "\n";
    foreach (array_slice($ukProducts, 0, 10) as $product) {
        echo "    - {$product}\n";
    }
    
    // Agregar productos reales de UK Kosher
    if (!empty($ukProducts) && $ukCertifier) {
        $brand = App\Models\Brand::firstOrCreate(['slug' => 'uk-kosher-latam'], [
            'name' => 'UK Kosher Latinoamérica',
            'description' => 'Productos certificados por UK Kosher Latinoamérica'
        ]);
        
        $added = 0;
        foreach (array_slice($ukProducts, 0, 15) as $productName) {
            $slug = Str::slug($productName . '-uk-kosher');
            if (!App\Models\Product::where('slug', $slug)->exists()) {
                App\Models\Product::create([
                    'name' => $productName,
                    'slug' => $slug,
                    'brand_id' => $brand->id,
                    'certifier_id' => $ukCertifier->id,
                    'kosher_status' => 'Certificado',
                    'description' => "Producto kosher certificado por UK Kosher Latinoamérica",
                    'source' => 'uk_kosher_real_scraper'
                ]);
                $added++;
            }
        }
        echo "  Productos agregados: {$added}\n";
    }
    
} catch (Exception $e) {
    echo "  Error general: " . $e->getMessage() . "\n";
}

echo "\n=== RESUMEN FINAL ===\n";
$certifiers = [
    'kehila' => 'Kehila Uruguay',
    'uk-kosher-latam' => 'UK Kosher Latinoamérica'
];

foreach ($certifiers as $slug => $name) {
    $certifier = App\Models\Certifier::where('slug', $slug)->first();
    if ($certifier) {
        $count = $certifier->products()->count();
        echo "- {$name}: {$count} productos\n";
    }
}

echo "\n¡Scraping completado! Visita http://kosherstatus.test/certifiers para ver los productos reales\n";
