<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRODUCTOS RECIENTES KMD ===\n";

$recent = \App\Models\Product::latest()->take(5)->get(['id', 'name', 'barcode', 'source']);

foreach ($recent as $p) {
    echo 'ID: ' . $p->id . ' | Nombre: ' . $p->name . ' | Barcode: ' . ($p->barcode ?? 'N/A') . ' | Source: ' . $p->source . "\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
