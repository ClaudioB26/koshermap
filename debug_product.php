<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS DE PRODUCTO OU ===\n";

// Obtener un producto OU
$product = App\Models\Product::where('source', 'ou_api')->orWhere('source', 'ou_api_intelligent')->first();

if (!$product) {
    echo "No se encontraron productos OU\n";
    exit;
}

echo "ID: " . $product->id . "\n";
echo "Nombre: " . $product->name . "\n";
echo "Brand ID: " . $product->brand_id . "\n";
echo "Source: " . $product->source . "\n";

// Obtener marca
$brand = App\Models\Brand::find($product->brand_id);
$brandName = $brand ? $brand->name : 'N/A';
echo "Brand Name: " . $brandName . "\n";

echo "\n=== BÚSQUEDAS MANUALES EN OFF ===\n";

// Probar diferentes búsquedas
$searchTerms = [
    $product->name,
    $brandName,
    $brandName . ' ' . $product->name,
    substr($product->name, 0, 20),
    str_replace(['"', "'", '(', ')', '[', ']'], '', $product->name)
];

foreach ($searchTerms as $index => $term) {
    echo "\n--- BÚSQUEDA " . ($index + 1) . ": '$term' ---\n";
    
    $params = [
        'search_terms' => $term,
        'search_simple' => 1,
        'action' => 'process',
        'json' => 1,
        'page_size' => 10
    ];
    
    $url = 'https://world.openfoodfacts.org/cgi/search.pl?' . http_build_query($params);
    echo "URL: " . $url . "\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(15)
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0'
        ])
        ->get($url);
    
    echo "Status Code: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "Resultados encontrados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $i => $offProduct) {
                echo "\n  Producto " . ($i + 1) . ":\n";
                echo "    Nombre: " . ($offProduct['product_name'] ?? 'N/A') . "\n";
                echo "    Marca: " . ($offProduct['brands'] ?? 'N/A') . "\n";
                echo "    Barcode: " . ($offProduct['code'] ?? 'N/A') . "\n";
                echo "    Categorías: " . ($offProduct['categories'] ?? 'N/A') . "\n";
                
                // Calcular similitud
                $offName = $offProduct['product_name'] ?? '';
                $offBrand = $offProduct['brands'] ?? '';
                
                similar_text(strtolower($product->name), strtolower($offName), $nameSimilarity);
                similar_text(strtolower($brandName), strtolower($offBrand), $brandSimilarity);
                
                echo "    Similitud Nombre: " . round($nameSimilarity, 1) . "%\n";
                echo "    Similitud Marca: " . round($brandSimilarity, 1) . "%\n";
            }
        }
    } else {
        echo "ERROR: " . $response->body() . "\n";
    }
    
    // Esperar 2 segundos entre requests
    sleep(2);
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
