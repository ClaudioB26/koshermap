<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE LOGIN EN OPEN FOOD FACTS ===\n";

// 1. Intentar login
echo "Intentando login con claudiob...\n";

$loginResponse = \Illuminate\Support\Facades\Http::asForm()
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive',
        'Upgrade-Insecure-Requests' => '1'
    ])
    ->post('https://world.openfoodfacts.org/sign-in', [
        'user_id' => 'claudiob',
        'password' => 'TU_PASSWORD_AQUI'  // Necesitas poner tu password
    ]);

echo "Status Code Login: " . $loginResponse->status() . "\n";
echo "Headers Login: " . json_encode($loginResponse->headers(), JSON_PRETTY_PRINT) . "\n";

// 2. Obtener cookies
$cookies = $loginResponse->cookies();
echo "Cookies recibidas: " . json_encode($cookies, JSON_PRETTY_PRINT) . "\n";

// 3. Usar cookies para hacer búsqueda
echo "\nIntentando búsqueda con cookies...\n";

$searchResponse = \Illuminate\Support\Facades\Http::withCookies($cookies)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'application/json',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive',
        'Cache-Control' => 'max-age=0'
    ])
    ->get('https://world.openfoodfacts.org/cgi/search.pl', [
        'search_terms' => 'Coca Cola',
        'search_simple' => 1,
        'action' => 'process',
        'json' => 1,
        'page_size' => 5
    ]);

echo "Status Code Búsqueda: " . $searchResponse->status() . "\n";

if ($searchResponse->successful()) {
    $data = $searchResponse->json();
    $products = $data['products'] ?? [];
    echo "Resultados encontrados: " . count($products) . "\n";
    
    if (!empty($products)) {
        foreach ($products as $i => $product) {
            echo "\n  Producto " . ($i + 1) . ":\n";
            echo "    Nombre: " . ($product['product_name'] ?? 'N/A') . "\n";
            echo "    Marca: " . ($product['brands'] ?? 'N/A') . "\n";
            echo "    Barcode: " . ($product['code'] ?? 'N/A') . "\n";
        }
    }
} else {
    echo "Error en búsqueda: " . $searchResponse->body() . "\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
