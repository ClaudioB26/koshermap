<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICANDO CERTIFICADORAS ===\n\n";

$certifiers = App\Models\Certifier::orderBy('name')->get();

echo "Total certificadoras: " . $certifiers->count() . "\n\n";

foreach ($certifiers as $certifier) {
    $productCount = $certifier->products()->count();
    $status = $productCount > 0 ? "VISIBLE" : "OCULTA (sin productos)";
    
    echo "- {$certifier->name} ({$certifier->slug})\n";
    echo "  Productos: {$productCount}\n";
    echo "  Estado: {$status}\n";
    echo "  Website: {$certifier->website}\n\n";
}

echo "=== CERTIFICADORAS VISIBLES EN /CERTIFIERS ===\n";
$visibleCertifiers = App\Models\Certifier::withCount('products')
    ->having('products_count', '>', 0)
    ->orderBy('name')
    ->get();

echo "Certificadoras visibles: " . $visibleCertifiers->count() . "\n";
foreach ($visibleCertifiers as $certifier) {
    echo "- {$certifier->name}: {$certifier->products_count} productos\n";
}

echo "\n=== CERTIFICADORAS OCULTAS (SIN PRODUCTOS) ===\n";
$hiddenCertifiers = App\Models\Certifier::withCount('products')
    ->having('products_count', '=', 0)
    ->orderBy('name')
    ->get();

echo "Certificadoras ocultas: " . $hiddenCertifiers->count() . "\n";
foreach ($hiddenCertifiers as $certifier) {
    echo "- {$certifier->name}\n";
}

echo "\n=== RECOMENDACIÓN ===\n";
if ($hiddenCertifiers->count() > 0) {
    echo "Las siguientes certificadoras necesitan productos para aparecer:\n";
    foreach ($hiddenCertifiers as $certifier) {
        echo "- {$certifier->name}\n";
    }
    echo "\nEjecuta: php run_new_scrapers.php para intentar agregar productos\n";
} else {
    echo "¡Todas las certificadoras tienen productos!\n";
}
