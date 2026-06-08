<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== LOGIN REAL EN OPEN FOOD FACTS ===\n";

// 1. Obtener página de login correcta
echo "1. Obteniendo página de login...\n";

$loginPage = \Illuminate\Support\Facades\Http::timeout(15)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive'
    ])
    ->get('https://world.openfoodfacts.org/cgi/user.pl');

echo "Status Login Page: " . $loginPage->status() . "\n";

// 2. Extraer campos ocultos (CSRF, etc.)
$html = $loginPage->body();
$hiddenFields = [];

if (preg_match_all('/<input[^>]*type=["\']hidden["\'][^>]*name=["\']([^"\']+)["\'][^>]*value=["\']([^"\']*)["\'][^>]*>/i', $html, $matches)) {
    foreach ($matches[1] as $i => $name) {
        $hiddenFields[$name] = $matches[2][$i];
    }
}

echo "Campos ocultos encontrados: " . json_encode($hiddenFields, JSON_PRETTY_PRINT) . "\n";

// 3. Intentar login
echo "\n2. Intentando login...\n";

$loginData = array_merge($hiddenFields, [
    'userid' => 'claudiob',
    'password' => 'H7.JmdD@XKh!2um',  // Password real
    'action' => 'process'  // Mantener el action original
]);

echo "Datos de login: " . json_encode($loginData, JSON_PRETTY_PRINT) . "\n";

$loginResponse = \Illuminate\Support\Facades\Http::asForm()
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive',
        'Referer' => 'https://world.openfoodfacts.org/cgi/user.pl'
    ])
    ->post('https://world.openfoodfacts.org/cgi/user.pl', $loginData);

echo "Status Login: " . $loginResponse->status() . "\n";

// 5. Verificar si el login fue exitoso
$body = $loginResponse->body();
echo "Response body (primeros 500 chars): " . substr($body, 0, 500) . "...\n";

if (strpos($body, 'Invalid') !== false || strpos($body, 'Error') !== false) {
    echo "❌ Error en login: " . substr($body, 0, 300) . "...\n";
} elseif (strpos($body, 'Welcome') !== false || strpos($body, 'claudiob') !== false) {
    echo "✅ Login exitoso!\n";
} elseif (strpos($body, 'sign-in') !== false || strpos($body, 'Sign in') !== false) {
    echo "❌ Login fallido - todavía en página de login\n";
} else {
    echo "⚠️ Estado de login desconocido\n";
}

// 4. Verificar cookies (después de verificar el body)
$cookies = $loginResponse->cookies();
$cookieArray = [];

if (!empty($cookies)) {
    echo "✅ Cookies recibidas:\n";
    foreach ($cookies as $name => $cookie) {
        echo "  $name: " . $cookie['Value'] . "\n";
        $cookieArray[$name] = $cookie['Value'];
    }
} else {
    echo "❌ No se recibieron cookies\n";
    echo "Headers completos: " . json_encode($loginResponse->headers(), JSON_PRETTY_PRINT) . "\n";
}

// 6. Probar búsqueda con cookies
if (!empty($cookieArray)) {
    echo "\n3. Probando búsqueda con cookies...\n";
    
    $searchResponse = \Illuminate\Support\Facades\Http::withCookies($cookieArray, 'world.openfoodfacts.org')
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive'
        ])
        ->get('https://world.openfoodfacts.org/cgi/search.pl', [
            'search_terms' => 'Coca Cola',
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => 5
        ]);
    
    echo "Status Búsqueda: " . $searchResponse->status() . "\n";
    
    if ($searchResponse->successful()) {
        $data = $searchResponse->json();
        $products = $data['products'] ?? [];
        echo "🎉 Resultados encontrados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $i => $product) {
                echo "\n  Producto " . ($i + 1) . ":\n";
                echo "    Nombre: " . ($product['product_name'] ?? 'N/A') . "\n";
                echo "    Marca: " . ($product['brands'] ?? 'N/A') . "\n";
                echo "    Barcode: " . ($product['code'] ?? 'N/A') . "\n";
            }
        }
    } else {
        echo "Error en búsqueda: " . substr($searchResponse->body(), 0, 200) . "...\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
