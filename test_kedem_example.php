<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== EJEMPLO: Kedem Grape Juice Red ===\n";

// Token de autenticación
$authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

// Producto OU
$productName = 'Kedem Grape Juice Red';
$brandName = 'Kedem';

echo "Producto OU: '$productName'\n";
echo "Marca: '$brandName'\n\n";

// Token de autenticación
$authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

// Primero, vamos a normalizar como lo hace el sistema
require_once 'app/Services/ProductTextNormalizer.php';

$normalizedProduct = App\Services\ProductTextNormalizer::normalize($productName);
$normalizedBrand = App\Services\ProductTextNormalizer::normalizeBrand($brandName);

echo "=== NORMALIZACIÓN DEL SISTEMA ===\n";
echo "Producto normalizado: '$normalizedProduct'\n";
echo "Marca normalizada: '$normalizedBrand'\n\n";

// Generar variaciones como lo hace el sistema
$variations = App\Services\ProductTextNormalizer::generateSearchVariations($productName, $brandName);

echo "=== VARIACIONES DE BÚSQUEDA DEL SISTEMA ===\n";
foreach ($variations as $i => $variation) {
    echo ($i + 1) . ". '" . $variation['query'] . "' (Precisión: " . $variation['precision'] . ")\n";
    
    // Probar esta búsqueda
    $response = \Illuminate\Support\Facades\Http::timeout(15)
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Authorization' => 'Basic ' . $authToken
        ])
        ->get('https://world.openfoodfacts.org/cgi/search.pl', [
            'search_terms' => $variation['query'],
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => 5
        ]);
    
    echo "   Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "   Resultados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $j => $product) {
                echo "     " . ($j + 1) . ". " . ($product['product_name'] ?? 'N/A') . " (" . ($product['brands'] ?? 'N/A') . ") Barcode: " . ($product['code'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "   Error: " . substr($response->body(), 0, 100) . "...\n";
    }
    
    echo "\n";
    sleep(1);
}

// Ahora probar búsquedas más inteligentes
echo "=== BÚSQUEDAS MÁS INTELIGENTES ===\n";

$smartSearches = [
    'Kedem Grape Juice',
    'Kedem Grape',
    'Grape Juice Red',
    'Kedem',
    'Grape Juice'
];

foreach ($smartSearches as $i => $term) {
    echo "Búsqueda " . ($i + 1) . ": '$term'\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(15)
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age-0',
            'Authorization' => 'Basic ' . $authToken
        ])
        ->get('https://world.openfoodfacts.org/cgi/search.pl', [
            'search_terms' => $term,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => 3
        ]);
    
    echo "   Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "   Resultados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $j => $product) {
                echo "     🎯 " . ($product['product_name'] ?? 'N/A') . " (" . ($product['brands'] ?? 'N/A') . ") Barcode: " . ($product['code'] ?? 'N/A') . "\n";
            }
        }
    }
    
    echo "\n";
    sleep(1);
}

echo "=== ANÁLISIS COMPLETADO ===\n";
