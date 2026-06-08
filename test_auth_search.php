<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE BÚSQUEDA CON AUTENTICACIÓN ===\n";

// Token de autenticación
$authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

// Probar diferentes búsquedas
$searchTerms = [
    'Coca Cola',
    'Oreo', 
    'Kellogg',
    'Heinz',
    'Nestle'
];

foreach ($searchTerms as $term) {
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
            'page_size' => 3
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
    
    // Pequeña pausa entre búsquedas
    sleep(1);
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
