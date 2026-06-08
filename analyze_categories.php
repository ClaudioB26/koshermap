<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS DE CATEGORÍAS ACTUALES ===\n";

// Obtener todas las categorías actuales
$categories = \App\Models\Category::withCount('products')->get();

echo "Total categorías: " . $categories->count() . "\n\n";

echo "=== CATEGORÍAS EXISTENTES ===\n";
foreach ($categories as $category) {
    echo "ID: {$category->id} | Nombre: {$category->name} | Slug: {$category->slug} | Productos: {$category->products_count}\n";
}

// Analizar productos por certificador
echo "\n=== PRODUCTOS POR CERTIFICADOR ===\n";
$certifiers = \App\Models\Certifier::withCount('products')->get();

foreach ($certifiers as $certifier) {
    echo "{$certifier->name}: {$certifier->products_count} productos\n";
    
    // Mostrar categorías de este certificador
    $categories = \App\Models\Product::where('certifier_id', $certifier->id)
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('categories.name', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('categories.id', 'categories.name')
        ->orderBy('count', 'desc')
        ->get();
    
    foreach ($categories as $cat) {
        echo "  - {$cat->name}: {$cat->count} productos\n";
    }
    echo "\n";
}

// Mostrar ejemplos de productos por categoría actual
echo "=== EJEMPLOS DE PRODUCTOS POR CATEGORÍA ===\n";
$topCategories = \App\Models\Category::withCount('products')
    ->orderBy('products_count', 'desc')
    ->limit(10)
    ->get();

foreach ($topCategories as $category) {
    echo "\nCategoría: {$category->name} ({$category->products_count} productos)\n";
    
    $products = \App\Models\Product::where('category_id', $category->id)
        ->limit(5)
        ->get(['name', 'brand_id']);
    
    foreach ($products as $product) {
        $brand = $product->brand ? $product->brand->name : 'Unknown';
        echo "  - {$product->name} ({$brand})\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
