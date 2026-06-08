<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESTAURANDO CATEGORÍAS ORIGINALES DE PRODUCTOS ===\n";

// Como no tenemos backup, voy a categorizar los productos basados en sus nombres
// usando el servicio de migración que creamos

require_once 'app/Services/CategoryMigrationService.php';

$service = new \App\Services\CategoryMigrationService();

// Obtener todos los productos sin categoría
$products = \App\Models\Product::whereNull('category_id')->get();

echo "Productos sin categoría: {$products->count()}\n";

$categorized = 0;
$notFound = 0;

foreach ($products as $product) {
    $categorySlug = $service->categorizeByKeywords($product->name);
    
    if ($categorySlug) {
        $category = \App\Models\Category::where('slug', $categorySlug)->first();
        if ($category) {
            $product->category_id = $category->id;
            $product->save();
            $categorized++;
            
            echo "Categorizado: '{$product->name}' -> '{$category->name}'\n";
        } else {
            $notFound++;
            echo "Categoría no encontrada: {$categorySlug}\n";
        }
    } else {
        $notFound++;
        echo "No se pudo categorizar: {$product->name}\n";
    }
    
    if ($categorized % 100 === 0 && $categorized > 0) {
        echo "Procesados: {$categorized} productos categorizados\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Productos categorizados: {$categorized}\n";
echo "No encontrados: {$notFound}\n";
echo "Total procesados: " . ($categorized + $notFound) . "\n";

// Mostrar estadísticas finales
echo "\n=== ESTADÍSTICAS FINALES ===\n";
$withCategory = \App\Models\Product::whereNotNull('category_id')->count();
$withoutCategory = \App\Models\Product::whereNull('category_id')->count();

echo "Con categoría: {$withCategory}\n";
echo "Sin categoría: {$withoutCategory}\n";
echo "Total: " . ($withCategory + $withoutCategory) . "\n";

// Mostrar categorías más populares
echo "\n=== CATEGORÍAS MÁS POPULARES ===\n";
$topCategories = \App\Models\Category::withCount('products')
    ->orderBy('products_count', 'desc')
    ->limit(10)
    ->get();

foreach ($topCategories as $category) {
    echo "{$category->name}: {$category->products_count} productos\n";
}

echo "\n=== PROCESO COMPLETADO ===\n";
