<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== BUSCANDO ENLACE DE LOGIN REAL ===\n";

// Obtener la página principal
$homePage = \Illuminate\Support\Facades\Http::timeout(15)
    ->withOptions(['verify' => false])
    ->withHeaders([
        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate',
        'Connection' => 'keep-alive'
    ])
    ->get('https://world.openfoodfacts.org');

echo "Status Home: " . $homePage->status() . "\n";

$html = $homePage->body();

// Buscar todos los enlaces que contengan login o sign in
if (preg_match_all('/<a[^>]*href=["\']([^"\']+)["\'][^>]*>([^<]*login[^<]*|[^<]*sign.?in[^<]*)<\/a>/i', $html, $matches)) {
    echo "Enlaces de login encontrados:\n";
    foreach ($matches[1] as $i => $url) {
        echo "  " . ($i + 1) . ". " . $matches[2][$i] . " -> " . $url . "\n";
        
        // Probar cada enlace
        echo "     Probando: $url\n";
        
        $response = \Illuminate\Support\Facades\Http::timeout(10)
            ->withOptions(['verify' => false])
            ->withHeaders([
                'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
            ])
            ->get($url);
        
        echo "     Status: " . $response->status() . "\n";
        
        if ($response->successful()) {
            $body = $response->body();
            
            // Buscar formularios de login
            if (preg_match_all('/<form[^>]*>(.*?)<\/form>/is', $body, $formMatches)) {
                foreach ($formMatches[0] as $j => $form) {
                    if (preg_match('/password/i', $form) && preg_match('/userid|user|email/i', $form)) {
                        echo "     ✅ Formulario de login encontrado en este enlace!\n";
                        
                        // Extraer action
                        if (preg_match('/action=["\']([^"\']+)["\']/', $form, $actionMatch)) {
                            echo "     Action: " . $actionMatch[1] . "\n";
                        }
                        
                        // Extraer inputs relevantes
                        if (preg_match_all('/<input[^>]*type=["\']?(text|password|hidden|submit)["\']?[^>]*name=["\']([^"\']*)["\'][^>]*value=["\']([^"\']*)["\']?[^>]*>/i', $form, $inputMatches)) {
                            echo "     Inputs relevantes:\n";
                            foreach ($inputMatches[1] as $k => $type) {
                                $name = $inputMatches[2][$k];
                                $value = $inputMatches[3][$k] ?? '';
                                echo "       $name: type=$type, value=$value\n";
                            }
                        }
                        
                        // Guardar este enlace para probar después
                        $loginUrl = $url;
                        $loginForm = $form;
                        break;
                    }
                }
            }
        }
        
        echo "\n";
    }
}

// Si encontramos un formulario de login, probarlo
if (isset($loginUrl) && isset($loginForm)) {
    echo "=== PROBANDO LOGIN ENCONTRADO ===\n";
    echo "URL: $loginUrl\n";
    
    // Extraer los datos necesarios del formulario
    $hiddenFields = [];
    if (preg_match_all('/<input[^>]*type=["\']hidden["\'][^>]*name=["\']([^"\']+)["\'][^>]*value=["\']([^"\']*)["\'][^>]*>/i', $loginForm, $matches)) {
        foreach ($matches[1] as $i => $name) {
            $hiddenFields[$name] = $matches[2][$i];
        }
    }
    
    echo "Campos ocultos: " . json_encode($hiddenFields, JSON_PRETTY_PRINT) . "\n";
    
    // Probar login
    $loginData = array_merge($hiddenFields, [
        'userid' => 'claudiob',
        'password' => 'H7.JmdD@XKh!2um'
    ]);
    
    echo "Datos de login: " . json_encode($loginData, JSON_PRETTY_PRINT) . "\n";
    
    // Extraer action del formulario
    $action = 'https://world.openfoodfacts.org/cgi/user.pl';
    if (preg_match('/action=["\']([^"\']+)["\']/', $loginForm, $actionMatch)) {
        $action = $actionMatch[1];
        if (!str_starts_with($action, 'http')) {
            $action = 'https://world.openfoodfacts.org' . $action;
        }
    }
    
    echo "Action final: $action\n";
    
    $loginResponse = \Illuminate\Support\Facades\Http::asForm()
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Referer' => $loginUrl
        ])
        ->post($action, $loginData);
    
    echo "Status Login: " . $loginResponse->status() . "\n";
    
    $body = $loginResponse->body();
    echo "Response (primeros 300 chars): " . substr($body, 0, 300) . "...\n";
    
    if (strpos($body, 'Welcome') !== false || strpos($body, 'claudiob') !== false) {
        echo "✅ LOGIN EXITOSO!\n";
    } elseif (strpos($body, 'Invalid') !== false || strpos($body, 'Error') !== false) {
        echo "❌ Error en login\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
