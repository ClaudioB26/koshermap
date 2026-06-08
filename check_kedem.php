<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICANDO PRODUCTO KEDEM ===\n";

$product = \App\Models\Product::where('name', 'Kedem Grape Juice Red')->first();

if ($product) {
    echo "ID: " . $product->id . "\n";
    echo "Nombre: " . $product->name . "\n";
    echo "Source: " . $product->source . "\n";
    echo "Barcode: " . ($product->barcode ?? 'N/A') . "\n";
    echo "Brand: " . ($product->brand->name ?? 'N/A') . "\n";
    echo "Brand ID: " . $product->brand_id . "\n";
    
    // También buscar productos con barcode
    echo "\n=== PRODUCTOS CON BARCODE ===\n";
    $withBarcode = \App\Models\Product::whereNotNull('barcode')->where('barcode', '!=', '')->limit(5)->get();
    foreach ($withBarcode as $p) {
        echo "- " . $p->name . " (" . $p->barcode . ")\n";
    }
} else {
    echo "Producto Kedem no encontrado\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
