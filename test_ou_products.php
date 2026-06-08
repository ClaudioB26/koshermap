<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PROBANDO PRODUCTOS OU REALES ===\n";

// Token de autenticación
$authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

// Productos OU que estamos buscando
$ouProducts = [
    'lux garden burger curry e peperoncino garden burger curry and chili pepper',
    'lux garden burger',
    'garden burger curry e peperoncino garden burger curry and chili pepper',
    'garden burger curry',
    'lux',
    'garden burger',
    'curry burger',
    'peperoncino'
];

foreach ($ouProducts as $term) {
    echo "\n--- Buscando: '$term' ---\n";
    
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
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "Resultados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $i => $product) {
                echo "\n  Producto " . ($i + 1) . ":\n";
                echo "    Nombre: " . ($product['product_name'] ?? 'N/A') . "\n";
                echo "    Marca: " . ($product['brands'] ?? 'N/A') . "\n";
                echo "    Barcode: " . ($product['code'] ?? 'N/A') . "\n";
                echo "    Categorías: " . ($product['categories'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "Error: " . substr($response->body(), 0, 200) . "...\n";
    }
    
    sleep(1);
}

// También probar términos más genéricos
echo "\n=== PROBANDO TÉRMINOS MÁS GENÉRICOS ===\n";

$genericTerms = [
    'burger',
    'garden',
    'curry',
    'vegetarian burger',
    'plant based burger'
];

foreach ($genericTerms as $term) {
    echo "\n--- Buscando genérico: '$term' ---\n";
    
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
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "Resultados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $i => $product) {
                echo "  - " . ($product['product_name'] ?? 'N/A') . " (" . ($product['brands'] ?? 'N/A') . ") Barcode: " . ($product['code'] ?? 'N/A') . "\n";
            }
        }
    }
    
    sleep(1);
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
