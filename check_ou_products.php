<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS DE PRODUCTOS OU PARA MATCHING ===\n";

// Obtener productos OU que no tienen matching inteligente
$ouProducts = \App\Models\Product::where('source', 'ou_api')
    ->orWhere('source', 'ou_api_intelligent')
    ->orderBy('id', 'desc')
    ->take(10)
    ->get();

echo "Productos OU recientes:\n";
foreach ($ouProducts as $i => $product) {
    echo ($i + 1) . ". ID: " . $product->id . " | Nombre: " . $product->name . " | Barcode: " . ($product->barcode ?? 'N/A') . " | Source: " . $product->source . "\n";
}

// Seleccionar algunos para probar manualmente
$testProducts = $ouProducts->take(3);

echo "\n=== PRUEBA MANUAL DE MATCHING ===\n";

require_once 'app/Services/IntelligentMatchingEngine.php';

foreach ($testProducts as $i => $product) {
    echo "\n--- Producto " . ($i + 1) . ": " . $product->name . " ---\n";
    
    $brandName = $product->brand->name ?? 'Unknown';
    echo "Marca: $brandName\n";
    
    try {
        $matchingEngine = new \App\Services\IntelligentMatchingEngine();
        $matchResult = $matchingEngine->matchProduct($product->name, $brandName);
        
        echo "Status: " . $matchResult['status'] . "\n";
        echo "Confidence: " . ($matchResult['confidence_score'] ?? 0) . "%\n";
        echo "Barcode: " . ($matchResult['off_barcode'] ?? 'N/A') . "\n";
        
        if (isset($matchResult['candidates'])) {
            echo "Candidatos: " . count($matchResult['candidates']) . "\n";
            
            // Mostrar el mejor candidato si hay
            if (!empty($matchResult['candidates'])) {
                $best = $matchResult['candidates'][0];
                echo "Mejor candidato: " . ($best['product_name'] ?? 'N/A') . "\n";
                echo "Marca OFF: " . ($best['brands'] ?? 'N/A') . "\n";
                echo "Barcode OFF: " . ($best['code'] ?? 'N/A') . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== BÚSQUEDA DIRECTA DE ALGUNOS PRODUCTOS ===\n";

// Token de autenticación
$authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

// Probar búsqueda directa para algunos productos
$directSearches = [
    'Goody Gumdrops',
    'Tote w/Toffee',
    'Gumdrops'
];

foreach ($directSearches as $term) {
    echo "\nBuscando: '$term'\n";
    
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
            'search_terms' => $term,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => 3
        ]);
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "Resultados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $j => $product) {
                echo "  🎯 " . ($product['product_name'] ?? 'N/A') . " (" . ($product['brands'] ?? 'N/A') . ") Barcode: " . ($product['code'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "Error: " . substr($response->body(), 0, 100) . "...\n";
    }
    
    sleep(1);
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
