<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FLUJO COMPLETO: OU → OFF ===\n";

// 1. Primero, buscar el producto en la base de datos OU
echo "\n1. BUSCANDO KEDEM EN BASE DE DATOS OU\n";

$ouProduct = \App\Models\Product::where('name', 'like', '%Kedem%')->orWhere('name', 'like', '%kedem%')->get();

if ($ouProduct->isEmpty()) {
    echo "❌ No se encontraron productos Kedem en la base OU\n";
    
    // Crear un producto Kedem de ejemplo
    echo "Creando producto Kedem de ejemplo...\n";
    
    $brand = \App\Models\Brand::firstOrCreate(['slug' => 'kedem'], ['name' => 'Kedem']);
    
    $ouProduct = \App\Models\Product::create([
        'name' => 'Kedem Grape Juice Red',
        'slug' => 'kedem-grape-juice-red',
        'brand_id' => $brand->id,
        'category' => 'Beverages',
        'country' => 'USA',
        'description' => 'Kedem Grape Juice Red - Kosher certified grape juice',
        'kosher_status' => 'certified',
        'certifier_id' => 1,
        'source' => 'ou_api'
    ]);
    
    echo "✅ Producto Kedem creado con ID: " . $ouProduct->id . "\n";
} else {
    echo "✅ Se encontraron " . $ouProduct->count() . " productos Kedem:\n";
    foreach ($ouProduct as $product) {
        echo "  - ID: " . $product->id . " | Nombre: " . $product->name . " | Barcode: " . ($product->barcode ?? 'N/A') . "\n";
    }
    
    // Usar el primero para el ejemplo
    $ouProduct = $ouProduct->first();
}

echo "\n2. DATOS DEL PRODUCTO OU SELECCIONADO:\n";
echo "ID: " . $ouProduct->id . "\n";
echo "Nombre: " . $ouProduct->name . "\n";
echo "Marca: " . ($ouProduct->brand->name ?? 'N/A') . "\n";
echo "Barcode actual: " . ($ouProduct->barcode ?? 'N/A') . "\n";
echo "Source: " . $ouProduct->source . "\n";

// 3. Ahora buscar en Open Food Facts usando el sistema de matching
echo "\n3. BUSCANDO EN OPEN FOOD FACTS CON MATCHING INTELIGENTE\n";

require_once 'app/Services/IntelligentMatchingEngine.php';
require_once 'app/Services/ProductTextNormalizer.php';

$matchingEngine = new \App\Services\IntelligentMatchingEngine();
$matchResult = $matchingEngine->matchProduct($ouProduct->name, $ouProduct->brand->name ?? '');

echo "Resultados del matching:\n";
echo "- Status: " . $matchResult['status'] . "\n";
echo "- Confidence: " . ($matchResult['confidence_score'] ?? 0) . "%\n";
echo "- Barcode OFF: " . ($matchResult['off_barcode'] ?? 'N/A') . "\n";
echo "- Image URL: " . ($matchResult['off_image_url'] ?? 'N/A') . "\n";

if (isset($matchResult['candidates'])) {
    echo "- Candidatos encontrados: " . count($matchResult['candidates']) . "\n";
    
    foreach ($matchResult['candidates'] as $i => $candidate) {
        echo "\n  Candidato " . ($i + 1) . ":\n";
        echo "    Nombre OFF: " . ($candidate['product_name'] ?? 'N/A') . "\n";
        echo "    Marca OFF: " . ($candidate['brands'] ?? 'N/A') . "\n";
        echo "    Barcode OFF: " . ($candidate['code'] ?? 'N/A') . "\n";
        echo "    Categorías: " . ($candidate['categories'] ?? 'N/A') . "\n";
    }
}

// 4. Búsqueda manual directa para comparar
echo "\n4. BÚSQUEDA MANUAL DIRECTA EN OFF\n";

$authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

$searchTerms = [
    'Kedem Grape Juice Red',
    'Kedem Grape Juice',
    'Kedem Grape',
    'Kedem',
    'Grape Juice Red'
];

foreach ($searchTerms as $term) {
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
            'page_size' => 5
        ]);
    
    echo "  Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "  Resultados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $i => $product) {
                echo "    🎯 " . ($product['product_name'] ?? 'N/A') . "\n";
                echo "       Marca: " . ($product['brands'] ?? 'N/A') . "\n";
                echo "       Barcode: " . ($product['code'] ?? 'N/A') . "\n";
                echo "       Categorías: " . ($product['categories'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "  Error: " . substr($response->body(), 0, 100) . "...\n";
    }
    
    sleep(1);
}

// 5. Verificar si el producto se actualizó
echo "\n5. VERIFICANDO ACTUALIZACIÓN DEL PRODUCTO\n";

$updatedProduct = \App\Models\Product::find($ouProduct->id);
echo "ID: " . $updatedProduct->id . "\n";
echo "Nombre: " . $updatedProduct->name . "\n";
echo "Source: " . $updatedProduct->source . "\n";
echo "Barcode después del matching: " . ($updatedProduct->barcode ?? 'N/A') . "\n";

if ($updatedProduct->barcode) {
    echo "🎉 ¡BARCODE ENCONTRADO Y GUARDADO!\n";
} else {
    echo "❌ No se guardó barcode\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
