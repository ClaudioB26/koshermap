<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PROBANDO SLUG SERVICE ===\n";

require_once 'app/Services/SlugService.php';

// Test cases
$testCases = [
    ['name' => 'King David" Chips', 'brand' => 'King David', 'barcode' => null],
    ['name' => '#3 Quick Rolled Oats', 'brand' => 'Richardson Milling', 'barcode' => '123456'],
    ['name' => 'Fiber Nuggets" Oat Bran Muffin', 'brand' => 'Komplet', 'barcode' => null],
    ['name' => '725 Flake Oil', 'brand' => 'Loders Croklaan', 'barcode' => '0073490132872'],
    ['name' => 'Yogurt griego artesanal (sin azúcar)', 'brand' => 'La Serenísima', 'barcode' => null],
];

echo "=== TEST DE GENERACIÓN DE SLUGS ===\n";
foreach ($testCases as $i => $test) {
    $slug = \App\Services\SlugService::generateProductSlug($test['name'], $test['brand'], $test['barcode']);
    echo "Test " . ($i + 1) . ":\n";
    echo "  Nombre: {$test['name']}\n";
    echo "  Marca: {$test['brand']}\n";
    echo "  Barcode: " . ($test['barcode'] ?? 'N/A') . "\n";
    echo "  Slug generado: {$slug}\n";
    echo "  Válido: " . (\App\Services\SlugService::isValidSlug($slug) ? 'SÍ' : 'NO') . "\n";
    echo "---\n";
}

// Limpiar slugs existentes
echo "\n=== LIMPIANDO SLUGS EXISTENTES ===\n";
$updated = \App\Services\SlugService::cleanExistingProductSlugs(20);
echo "Slugs actualizados: {$updated}\n";

// Mostrar algunos productos actualizados
echo "\n=== PRODUCTOS ACTUALIZADOS ===\n";
$products = \App\Models\Product::latest()->limit(5)->get(['id', 'name', 'slug']);

foreach ($products as $product) {
    echo "ID: {$product->id} | Nombre: {$product->name}\n";
    echo "Slug: {$product->slug}\n";
    echo "---\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
