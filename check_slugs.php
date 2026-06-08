<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS DE SLUGS ACTUALES ===\n";

// Revisar slugs de productos
$products = \App\Models\Product::select('id', 'name', 'slug', 'source')
    ->limit(10)
    ->get();

echo "=== SLUGS DE PRODUCTOS ===\n";
foreach ($products as $product) {
    echo "ID: {$product->id} | Nombre: {$product->name}\n";
    echo "Slug actual: {$product->slug}\n";
    echo "Slug esperado: " . \Illuminate\Support\Str::slug($product->name) . "\n";
    echo "Source: {$product->source}\n";
    echo "---\n";
}

// Revisar slugs de marcas
$brands = \App\Models\Brand::select('id', 'name', 'slug')
    ->limit(5)
    ->get();

echo "\n=== SLUGS DE MARCAS ===\n";
foreach ($brands as $brand) {
    echo "ID: {$brand->id} | Nombre: {$brand->name}\n";
    echo "Slug actual: {$brand->slug}\n";
    echo "Slug esperado: " . \Illuminate\Support\Str::slug($brand->name) . "\n";
    echo "---\n";
}

// Revisar slugs de categorías
$categories = \App\Models\Category::select('id', 'name', 'slug')
    ->limit(5)
    ->get();

echo "\n=== SLUGS DE CATEGORÍAS ===\n";
foreach ($categories as $category) {
    echo "ID: {$category->id} | Nombre: {$category->name}\n";
    echo "Slug actual: {$category->slug}\n";
    echo "Slug esperado: " . \Illuminate\Support\Str::slug($category->name) . "\n";
    echo "---\n";
}

// Buscar problemas comunes en slugs
echo "\n=== PROBLEMAS COMUNES ===\n";

$problems = [
    'slugs_vacios' => \App\Models\Product::whereNull('slug')->orWhere('slug', '')->count(),
    'slugs_duplicados' => \App\Models\Product::select('slug')->groupBy('slug')->havingRaw('COUNT(*) > 1')->count(),
    'slugs_con_espacios' => \App\Models\Product::where('slug', 'like', '% %')->count(),
    'slugs_con_caracteres_especiales' => \App\Models\Product::where('slug', 'regexp', '[^a-z0-9\-_]')->count(),
];

foreach ($problems as $problem => $count) {
    echo ucfirst(str_replace('_', ' ', $problem)) . ": {$count}\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
