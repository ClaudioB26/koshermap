<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PROBANDO AUTENTICACIÓN DIRECTA API ===\n";

// 1. Probar búsqueda con Basic Auth
echo "1. Probando búsqueda con Basic Auth...\n";

$basicAuthResponse = \Illuminate\Support\Facades\Http::timeout(15)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'application/json',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive'
    ])
    ->withBasicAuth('claudiob', 'H7.JmdD@XKh!2um')
    ->get('https://world.openfoodfacts.org/cgi/search.pl', [
        'search_terms' => 'Coca Cola',
        'search_simple' => 1,
        'action' => 'process',
        'json' => 1,
        'page_size' => 5
    ]);

echo "Status Basic Auth: " . $basicAuthResponse->status() . "\n";

if ($basicAuthResponse->successful()) {
    $data = $basicAuthResponse->json();
    echo "Resultados con Basic Auth: " . count($data['products'] ?? []) . "\n";
    
    if (!empty($data['products'])) {
        foreach ($data['products'] as $i => $product) {
            echo "\n  Producto " . ($i + 1) . ":\n";
            echo "    Nombre: " . ($product['product_name'] ?? 'N/A') . "\n";
            echo "    Marca: " . ($product['brands'] ?? 'N/A') . "\n";
            echo "    Barcode: " . ($product['code'] ?? 'N/A') . "\n";
        }
    }
} else {
    echo "Error Basic Auth: " . substr($basicAuthResponse->body(), 0, 200) . "...\n";
}

// 2. Probar con header de Authorization
echo "\n2. Probando con header Authorization...\n";

$token = base64_encode('claudiob:H7.JmdD@XKh!2um');

$authHeaderResponse = \Illuminate\Support\Facades\Http::timeout(15)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'application/json',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive',
        'Authorization' => 'Basic ' . $token
    ])
    ->get('https://world.openfoodfacts.org/cgi/search.pl', [
        'search_terms' => 'Coca Cola',
        'search_simple' => 1,
        'action' => 'process',
        'json' => 1,
        'page_size' => 5
    ]);

echo "Status Auth Header: " . $authHeaderResponse->status() . "\n";

if ($authHeaderResponse->successful()) {
    $data = $authHeaderResponse->json();
    echo "Resultados con Auth Header: " . count($data['products'] ?? []) . "\n";
} else {
    echo "Error Auth Header: " . substr($authHeaderResponse->body(), 0, 200) . "...\n";
}

// 3. Probar API v2 con auth
echo "\n3. Probando API v2 con auth...\n";

$v2AuthResponse = \Illuminate\Support\Facades\Http::timeout(15)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'application/json',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive'
    ])
    ->withBasicAuth('claudiob', 'H7.JmdD@XKh!2um')
    ->get('https://world.openfoodfacts.org/api/v2/search', [
        'search_terms' => 'Coca Cola',
        'page_size' => 5
    ]);

echo "Status API v2 Auth: " . $v2AuthResponse->status() . "\n";

if ($v2AuthResponse->successful()) {
    $data = $v2AuthResponse->json();
    echo "Resultados API v2: " . count($data['products'] ?? []) . "\n";
    
    if (!empty($data['products'])) {
        foreach ($data['products'] as $i => $product) {
            echo "\n  Producto " . ($i + 1) . ":\n";
            echo "    Nombre: " . ($product['product_name'] ?? 'N/A') . "\n";
            echo "    Marca: " . ($product['brands'] ?? 'N/A') . "\n";
            echo "    Barcode: " . ($product['code'] ?? 'N/A') . "\n";
        }
    }
} else {
    echo "Error API v2: " . substr($v2AuthResponse->body(), 0, 200) . "...\n";
}

// 4. Probar endpoint de auth específico
echo "\n4. Probando endpoint de auth...\n";

$authEndpoints = [
    'https://world.openfoodfacts.org/auth',
    'https://world.openfoodfacts.org/api/auth',
    'https://world.openfoodfacts.org/api/v1/auth',
    'https://world.openfoodfacts.org/cgi/auth.pl'
];

foreach ($authEndpoints as $endpoint) {
    echo "\nProbando endpoint: $endpoint\n";
    
    try {
        $authResponse = \Illuminate\Support\Facades\Http::timeout(10)
            ->withOptions(['verify' => false])
            ->withHeaders([
                'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])
            ->post($endpoint, [
                'username' => 'claudiob',
                'password' => 'H7.JmdD@XKh!2um'
            ]);
        
        echo "Status: " . $authResponse->status() . "\n";
        
        if ($authResponse->successful()) {
            echo "✅ Auth exitoso en: $endpoint\n";
            echo "Response: " . $authResponse->body() . "\n";
            break;
        } else {
            echo "Error: " . substr($authResponse->body(), 0, 100) . "...\n";
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
