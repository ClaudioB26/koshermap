<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS DEL CASO OAT BRAN MUFFIN ===\n";

// Producto OU
$ouProductName = 'Fiber Nuggets" Oat Bran Muffin';
$ouBrandName = 'Komplet';

echo "Producto OU: '$ouProductName'\n";
echo "Marca OU: '$ouBrandName'\n\n";

// Productos encontrados manualmente en OFF
$offProducts = [
    [
        'name' => 'Oat Bran Muffin Mix – Bob\'s Red Mill',
        'brand' => 'Bob\'s Red Mill',
        'barcode' => '0039978502247'
    ],
    [
        'name' => 'Oat bran supreme muffin mix, oat bran',
        'brand' => 'Krusteaz',
        'barcode' => '0041449300177'
    ],
    [
        'name' => 'Blueberry Raspberry oat bran muffins',
        'brand' => 'Trader Joe\'s',
        'barcode' => '00578561'
    ],
    [
        'name' => 'Low Fat Muffin Bran',
        'brand' => 'Quaker',
        'barcode' => '0055577105016'
    ],
    [
        'name' => 'Oat & Fruits Bran Muffins',
        'brand' => 'Schwartz Brothers Bakery',
        'barcode' => '0717887600420'
    ]
];

echo "=== PRODUCTOS ENCONTRADOS EN OFF ===\n";
foreach ($offProducts as $i => $product) {
    echo ($i + 1) . ". " . $product['name'] . "\n";
    echo "   Marca: " . $product['brand'] . "\n";
    echo "   Barcode: " . $product['barcode'] . "\n\n";
}

// Analizar matching con el sistema
require_once 'app/Services/IntelligentMatchingEngine.php';
require_once 'app/Services/ProductTextNormalizer.php';

echo "=== ANÁLISIS DE MATCHING ===\n";

$matchingEngine = new \App\Services\IntelligentMatchingEngine();
$matchResult = $matchingEngine->matchProduct($ouProductName, $ouBrandName);

echo "Status del matching: " . $matchResult['status'] . "\n";
echo "Confianza: " . ($matchResult['confidence_score'] ?? 0) . "%\n";
echo "Barcode: " . ($matchResult['off_barcode'] ?? 'N/A') . "\n";

// Mostrar variaciones de búsqueda
echo "\n=== VARIACIONES DE BÚSQUEDA QUE USA EL SISTEMA ===\n";
$variations = \App\Services\ProductTextNormalizer::generateSearchVariations($ouProductName, $ouBrandName);

foreach ($variations as $i => $variation) {
    echo ($i + 1) . ". '" . $variation['query'] . "' (Precisión: " . $variation['precision'] . ")\n";
}

// Probar cada variación manualmente
echo "\n=== PROBANDO VARIACIONES MANUALMENTE ===\n";

$authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

foreach ($variations as $i => $variation) {
    echo "\nVariación " . ($i + 1) . ": '" . $variation['query'] . "'\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(10)
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $authToken
        ])
        ->get('https://world.openfoodfacts.org/cgi/search.pl', [
            'search_terms' => $variation['query'],
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => 5
        ]);
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "Resultados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $j => $product) {
                echo "  🎯 " . ($product['product_name'] ?? 'N/A') . "\n";
                echo "     Marca: " . ($product['brands'] ?? 'N/A') . "\n";
                echo "     Barcode: " . ($product['code'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "Error: " . substr($response->body(), 0, 100) . "...\n";
    }
    
    sleep(1);
}

// Probar búsquedas más simples
echo "\n=== BÚSQUEDAS SIMPLIFICADAS ===\n";

$simpleSearches = [
    'Oat Bran Muffin',
    'Oat Bran Muffin Mix',
    'Muffin Oat Bran',
    'Oat Muffin'
];

foreach ($simpleSearches as $term) {
    echo "\nBuscando: '$term'\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(10)
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'application/json',
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
    }
    
    sleep(1);
}

echo "\n=== ANÁLISIS DE SIMILITUD ===\n";

// Calcular similitud entre nombres
$ouNormalized = \App\Services\ProductTextNormalizer::normalize($ouProductName);
echo "Producto OU normalizado: '$ouNormalized'\n\n";

foreach ($offProducts as $i => $product) {
    $offNormalized = \App\Services\ProductTextNormalizer::normalize($product['name']);
    $brandNormalized = \App\Services\ProductTextNormalizer::normalize($product['brand']);
    
    echo "Producto OFF " . ($i + 1) . ":\n";
    echo "  Nombre: " . $product['name'] . "\n";
    echo "  Normalizado: '$offNormalized'\n";
    
    // Calcular similitud
    $nameSimilarity = 0;
    similar_text($ouNormalized, $offNormalized, $nameSimilarity);
    
    echo "  Similitud de nombre: " . round($nameSimilarity, 1) . "%\n";
    
    // Verificar palabras clave
    $ouWords = explode(' ', $ouNormalized);
    $offWords = explode(' ', $offNormalized);
    
    $commonWords = array_intersect($ouWords, $offWords);
    echo "  Palabras comunes: " . implode(', ', $commonWords) . "\n";
    
    if ($nameSimilarity > 30) {
        echo "  ✅ POTENCIAL MATCH!\n";
    } else {
        echo "  ❌ Baja similitud\n";
    }
    
    echo "\n";
}

echo "=== CONCLUSIONES ===\n";
echo "1. El producto OU tiene un nombre muy específico y complejo\n";
echo "2. Los productos OFF tienen nombres más simples y comerciales\n";
echo "3. Las búsquedas del sistema son demasiado específicas\n";
echo "4. Necesitaríamos matching más flexible o búsqueda por categorías\n";

echo "\n=== ANÁLISIS COMPLETADO ===\n";
