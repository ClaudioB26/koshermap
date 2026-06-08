<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== EJECUTANDO SCRAPERS DE HOY ===\n\n";

// Configurar HTTP client para ignorar SSL
Http::macro('insecure', function () {
    return Http::withoutVerifying()->timeout(30);
});

// 1. Kehila Uruguay
echo "1. Ejecutando scraper Kehila Uruguay...\n";
try {
    Artisan::call('scrape:kehila');
    echo "Kehila Uruguay completado.\n\n";
} catch (Exception $e) {
    echo "Error en Kehila Uruguay: " . $e->getMessage() . "\n\n";
}

// 2. BDK Brasil
echo "2. Ejecutando scraper BDK Brasil...\n";
try {
    Artisan::call('scrape:bdk');
    echo "BDK Brasil completado.\n\n";
} catch (Exception $e) {
    echo "Error en BDK Brasil: " . $e->getMessage() . "\n\n";
}

// 3. Kosher Chile
echo "3. Ejecutando scraper Kosher Chile...\n";
try {
    Artisan::call('scrape:kosher-chile');
    echo "Kosher Chile completado.\n\n";
} catch (Exception $e) {
    echo "Error en Kosher Chile: " . $e->getMessage() . "\n\n";
}

// 4. UK Kosher Latinoamérica
echo "4. Ejecutando scraper UK Kosher Latinoamérica...\n";
try {
    Artisan::call('scrape:uk-kosher');
    echo "UK Kosher Latinoamérica completado.\n\n";
} catch (Exception $e) {
    echo "Error en UK Kosher Latinoamérica: " . $e->getMessage() . "\n\n";
}

echo "=== RESUMEN FINAL ===\n";
echo "Verificando productos por certificadora:\n";

$certifiers = [
    'kehila-uruguay' => 'Kehila Uruguay',
    'bdk-brasil' => 'BDK Brasil', 
    'kosher-chile' => 'Kosher Chile',
    'uk-kosher-latam' => 'UK Kosher Latinoamérica'
];

foreach ($certifiers as $slug => $name) {
    $certifierId = App\Models\Certifier::where('slug', $slug)->value('id');
    $productCount = $certifierId ? App\Models\Product::where('certifier_id', $certifierId)->count() : 0;
    echo "- {$name}: {$productCount} productos\n";
}

echo "\n¡Scrapers completados!\n";
echo "Ahora visita http://kosherstatus.test/certifiers para ver las certificadoras con productos.\n";
