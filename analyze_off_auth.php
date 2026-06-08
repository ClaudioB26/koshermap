<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS DE AUTENTICACIÓN OPEN FOOD FACTS ===\n";

// 1. Analizar página de login
echo "1. Analizando página de login...\n";

$loginPage = \Illuminate\Support\Facades\Http::timeout(15)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive'
    ])
    ->get('https://world.openfoodfacts.org/sign-in');

echo "Status Login Page: " . $loginPage->status() . "\n";

// Buscar form fields
$html = $loginPage->body();
echo "Buscando campos del formulario...\n";

// Buscar CSRF token u otros campos ocultos
if (preg_match('/<input[^>]*name=["\']([^"\']+)["\'][^>]*value=["\']([^"\']*)["\'][^>]*>/i', $html, $matches)) {
    echo "Campo oculto encontrado: " . $matches[1] . " = " . $matches[2] . "\n";
}

// Buscar action del form
if (preg_match('/<form[^>]*action=["\']([^"\']+)["\'][^>]*>/i', $html, $matches)) {
    echo "Form action: " . $matches[1] . "\n";
}

// 2. Intentar login básico
echo "\n2. Intentando login básico...\n";

$loginResponse = \Illuminate\Support\Facades\Http::asForm()
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive',
        'Referer' => 'https://world.openfoodfacts.org/sign-in'
    ])
    ->post('https://world.openfoodfacts.org/sign-in', [
        'user_id' => 'claudiob',
        'password' => 'test_password'  // Password de prueba
    ]);

echo "Status Login: " . $loginResponse->status() . "\n";
echo "Headers Response: " . json_encode($loginResponse->headers(), JSON_PRETTY_PRINT) . "\n";

// 3. Verificar si hay redirect o cookies
$cookies = $loginResponse->cookies();
if (!empty($cookies)) {
    echo "Cookies recibidas:\n";
    foreach ($cookies as $name => $cookie) {
        echo "  $name: " . $cookie['Value'] . "\n";
    }
} else {
    echo "No se recibieron cookies\n";
}

// 4. Revisar response body para errores
$body = $loginResponse->body();
if (strpos($body, 'Invalid') !== false || strpos($body, 'Error') !== false) {
    echo "Error encontrado en response: " . substr($body, 0, 200) . "...\n";
}

// 5. Probar búsqueda sin login para comparar
echo "\n3. Probando búsqueda sin login (baseline)...\n";

$searchResponse = \Illuminate\Support\Facades\Http::timeout(15)
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

echo "Status Búsqueda sin login: " . $searchResponse->status() . "\n";

if ($searchResponse->successful()) {
    $data = $searchResponse->json();
    echo "Resultados sin login: " . count($data['products'] ?? []) . "\n";
} else {
    echo "Error sin login: " . substr($searchResponse->body(), 0, 200) . "...\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
