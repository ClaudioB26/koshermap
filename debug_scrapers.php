<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG SCRAPERS - INTENTANDO OBTENER PRODUCTOS ===\n\n";

// Configurar HTTP client para ignorar SSL y ser más permisivo
Http::macro('debug', function () {
    return Http::withoutVerifying()
        ->timeout(60)
        ->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
            'Upgrade-Insecure-Requests' => '1'
        ]);
});

// 1. Kehila Uruguay - Intentar con URL directa
echo "1. Probando Kehila Uruguay con diferentes URLs...\n";
try {
    $urls = [
        'https://kehila.org.uy/kasher/productos/',
        'https://kehila.org.uy/kasher/',
        'https://kehila.org.uy/',
        'http://kehila.org.uy/kasher/productos/',
        'http://kehila.org.uy/kasher/',
        'http://kehila.org.uy/'
    ];
    
    foreach ($urls as $url) {
        echo "  Probando: {$url}\n";
        $response = Http::debug()->get($url);
        if ($response->successful()) {
            echo "    Éxito: " . strlen($response->body()) . " bytes\n";
            
            // Buscar patrones de productos
            if (preg_match('/producto|product/i', $response->body())) {
                echo "    Contiene mención de productos\n";
            }
            if (preg_match('/kosher/i', $response->body())) {
                echo "    Contiene mención de kosher\n";
            }
            break;
        } else {
            echo "    Error: " . $response->status() . "\n";
        }
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. BDK Brasil - Intentar con diferentes URLs
echo "2. Probando BDK Brasil con diferentes URLs...\n";
try {
    $urls = [
        'https://www.bdk.com.br/',
        'https://bdk.com.br/',
        'http://www.bdk.com.br/',
        'http://bdk.com.br/',
        'https://www.bdk.com.br/produtos/',
        'https://bdk.com.br/produtos/'
    ];
    
    foreach ($urls as $url) {
        echo "  Probando: {$url}\n";
        $response = Http::debug()->get($url);
        if ($response->successful()) {
            echo "    Éxito: " . strlen($response->body()) . " bytes\n";
            break;
        } else {
            echo "    Error: " . $response->status() . "\n";
        }
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Kosher Chile - Intentar con diferentes URLs
echo "3. Probando Kosher Chile con diferentes URLs...\n";
try {
    $urls = [
        'https://www.chilekosher.cl/',
        'https://chilekosher.cl/',
        'http://www.chilekosher.cl/',
        'http://chilekosher.cl/',
        'https://www.chilekosher.cl/productos-kosher/',
        'https://chilekosher.cl/productos-kosher/'
    ];
    
    foreach ($urls as $url) {
        echo "  Probando: {$url}\n";
        $response = Http::debug()->get($url);
        if ($response->successful()) {
            echo "    Éxito: " . strlen($response->body()) . " bytes\n";
            break;
        } else {
            echo "    Error: " . $response->status() . "\n";
        }
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n";

// 4. UK Kosher - Intentar con diferentes URLs
echo "4. Probando UK Kosher con diferentes URLs...\n";
try {
    $urls = [
        'https://ukkosher.org/',
        'http://ukkosher.org/',
        'https://www.ukkosher.org/',
        'http://www.ukkosher.org/'
    ];
    
    foreach ($urls as $url) {
        echo "  Probando: {$url}\n";
        $response = Http::debug()->get($url);
        if ($response->successful()) {
            echo "    Éxito: " . strlen($response->body()) . " bytes\n";
            break;
        } else {
            echo "    Error: " . $response->status() . "\n";
        }
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICANDO CERTIFICADORAS CREADAS ===\n";
$certifiers = [
    'kehila' => 'Kehila Uruguay',
    'bdk-brasil' => 'BDK Brasil',
    'kosher-chile' => 'Chile Kosher',
    'uk-kosher-latam' => 'UK Kosher Latinoamérica'
];

foreach ($certifiers as $slug => $name) {
    $certifier = App\Models\Certifier::where('slug', $slug)->first();
    if ($certifier) {
        echo "Certificadora '{$name}' existe en BD\n";
        echo "  ID: {$certifier->id}\n";
        echo "  Website: {$certifier->website}\n";
        echo "  Productos: " . $certifier->products()->count() . "\n\n";
    } else {
        echo "Certificadora '{$name}' NO existe en BD\n\n";
    }
}

echo "=== RECOMENDACIONES ===\n";
echo "1. Si los sitios responden, los scrapers necesitan ajustes\n";
echo "2. Si los sitios no responden, pueden estar caídos o bloqueados\n";
echo "3. Considerar usar productos de muestra para testing\n";
echo "4. Revisar si las URLs de los scrapers son correctas\n";
