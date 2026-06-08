<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== BUSCANDO URL DE LOGIN CORRECTA ===\n";

// Probar diferentes URLs de login
$loginUrls = [
    'https://world.openfoodfacts.org/sign-in',
    'https://world.openfoodfacts.org/sign_in', 
    'https://world.openfoodfacts.org/login',
    'https://world.openfoodfacts.org/user/sign-in',
    'https://world.openfoodfacts.org/user/sign_in',
    'https://world.openfoodfacts.org/cgi/user.pl',
    'https://world.openfoodfacts.org/cgi/session.pl'
];

foreach ($loginUrls as $url) {
    echo "\nProbando: $url\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(10)
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        ])
        ->get($url);
    
    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $body = $response->body();
        
        // Buscar formulario de login
        if (strpos($body, 'password') !== false && strpos($body, 'user') !== false) {
            echo "✅ FORMULARIO DE LOGIN ENCONTRADO!\n";
            
            // Extraer action del form
            if (preg_match('/<form[^>]*action=["\']([^"\']+)["\'][^>]*>/i', $body, $matches)) {
                echo "Form action: " . $matches[1] . "\n";
            }
            
            // Buscar campos del formulario
            if (preg_match_all('/<input[^>]*name=["\']([^"\']+)["\'][^>]*>/i', $body, $matches)) {
                echo "Campos encontrados: " . implode(', ', $matches[1]) . "\n";
            }
            
            break;
        }
    }
}

// También revisar la página principal
echo "\nRevisando página principal...\n";

$homeResponse = \Illuminate\Support\Facades\Http::timeout(10)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
    ])
    ->get('https://world.openfoodfacts.org');

echo "Status Home: " . $homeResponse->status() . "\n";

if ($homeResponse->successful()) {
    $body = $homeResponse->body();
    
    // Buscar links de login
    if (preg_match_all('/<a[^>]*href=["\']([^"\']+)["\'][^>]*>[^<]*login[^<]*<\/a>/i', $body, $matches)) {
        echo "Links de login encontrados:\n";
        foreach ($matches[1] as $link) {
            echo "  - $link\n";
        }
    }
    
    // Buscar sign in
    if (preg_match_all('/<a[^>]*href=["\']([^"\']+)["\'][^>]*>[^<]*sign in[^<]*<\/a>/i', $body, $matches)) {
        echo "Links de sign in encontrados:\n";
        foreach ($matches[1] as $link) {
            echo "  - $link\n";
        }
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
