<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS COMPLETO DEL FORMULARIO DE LOGIN ===\n";

// Obtener página de login
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

echo "Status: " . $loginPage->status() . "\n";

// Analizar el HTML completo
$html = $loginPage->body();

// Buscar todos los formularios
if (preg_match_all('/<form[^>]*>(.*?)<\/form>/is', $html, $formMatches)) {
    echo "Formularios encontrados: " . count($formMatches[0]) . "\n\n";
    
    foreach ($formMatches[0] as $i => $form) {
        echo "--- FORMULARIO " . ($i + 1) . " ---\n";
        
        // Extraer action
        if (preg_match('/action=["\']([^"\']+)["\']/', $form, $actionMatch)) {
            echo "Action: " . $actionMatch[1] . "\n";
        }
        
        // Extraer method
        if (preg_match('/method=["\']([^"\']+)["\']/', $form, $methodMatch)) {
            echo "Method: " . $methodMatch[1] . "\n";
        }
        
        // Extraer todos los inputs
        if (preg_match_all('/<input[^>]*>/i', $form, $inputMatches)) {
            echo "Inputs:\n";
            foreach ($inputMatches[0] as $input) {
                $name = '';
                $type = '';
                $value = '';
                
                if (preg_match('/name=["\']([^"\']*)["\']/', $input, $nameMatch)) {
                    $name = $nameMatch[1];
                }
                if (preg_match('/type=["\']([^"\']*)["\']/', $input, $typeMatch)) {
                    $type = $typeMatch[1];
                }
                if (preg_match('/value=["\']([^"\']*)["\']/', $input, $valueMatch)) {
                    $value = $valueMatch[1];
                }
                
                echo "  $name: type=$type, value=$value\n";
            }
        }
        
        // Buscar botones submit
        if (preg_match_all('/<button[^>]*>(.*?)<\/button>/is', $form, $buttonMatches)) {
            echo "Botones:\n";
            foreach ($buttonMatches[0] as $button) {
                if (preg_match('/name=["\']([^"\']*)["\']/', $button, $nameMatch)) {
                    $name = $nameMatch[1];
                } else {
                    $name = 'sin_nombre';
                }
                echo "  $name: " . strip_tags($button) . "\n";
            }
        }
        
        echo "\n";
    }
} else {
    echo "No se encontraron formularios\n";
}

// Buscar cualquier referencia a login en el HTML
if (preg_match_all('/login|sign.?in/i', $html, $loginRefs)) {
    echo "Referencias a login encontradas: " . count($loginRefs[0]) . "\n";
    foreach (array_unique($loginRefs[0]) as $ref) {
        echo "  - $ref\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
