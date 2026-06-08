<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COMPLETANDO MIGRACIÓN DE CATEGORÍAS ===\n";

require_once 'app/Services/CategoryMigrationService.php';

$service = new \App\Services\CategoryMigrationService();

// 1. Primero crear la nueva estructura de árbol (si no existe)
echo "1. Verificando estructura de árbol...\n";
$treeCategories = \App\Models\Category::where('parent_id', '!=', null)->count();
if ($treeCategories == 0) {
    echo "Creando estructura de árbol...\n";
    // Ejecutar el seeder del árbol si no existe
    include_once 'database/seeders/CategoryTreeSeeder.php';
    $seeder = new \Database\Seeders\CategoryTreeSeeder();
    $seeder->run();
} else {
    echo "Estructura de árbol ya existe con {$treeCategories} categorías\n";
}

// 2. Migrar productos con categorías antiguas
echo "\n2. Migrando productos con categorías existentes...\n";
$result = $service->migrateAllProducts(false); // false = ejecutar realmente

// 3. Para los productos sin categoría, intentar categorizar por palabras clave
echo "\n3. Categorizando productos restantes por palabras clave...\n";
$uncategorized = \App\Models\Product::whereNull('category_id')->get();
$additionalCategorized = 0;

foreach ($uncategorized as $product) {
    $categorySlug = $service->categorizeByKeywords($product->name);
    
    if ($categorySlug) {
        $category = \App\Models\Category::where('slug', $categorySlug)->first();
        if ($category) {
            $product->category_id = $category->id;
            $product->save();
            $additionalCategorized++;
            
            if ($additionalCategorized % 50 === 0) {
                echo "Categorizados adicionales: {$additionalCategorized}\n";
            }
        }
    }
}

// 4. Estadísticas finales
echo "\n=== ESTADÍSTICAS FINALES ===\n";
$withCategory = \App\Models\Product::whereNotNull('category_id')->count();
$withoutCategory = \App\Models\Product::whereNull('category_id')->count();

echo "Migración original: {$result['migrated']} productos\n";
echo "Categorizados por palabras clave: {$additionalCategorized}\n";
echo "Total con categoría: {$withCategory}\n";
echo "Sin categoría: {$withoutCategory}\n";
echo "Porcentaje categorizado: " . round(($withCategory / ($withCategory + $withoutCategory)) * 100, 2) . "%\n";

// 5. Mostrar distribución por categorías principales
echo "\n=== DISTRIBUCIÓN POR CATEGORÍAS PRINCIPALES ===\n";
$mainCategories = \App\Models\Category::whereNull('parent_id')
    ->withCount('products')
    ->orderBy('products_count', 'desc')
    ->get();

foreach ($mainCategories as $category) {
    echo "{$category->name}: {$category->products_count} productos\n";
}

// 6. Mostrar subcategorías más populares
echo "\n=== SUBCATEGORÍAS MÁS POPULARES ===\n";
$subCategories = \App\Models\Category::whereNotNull('parent_id')
    ->withCount('products')
    ->orderBy('products_count', 'desc')
    ->limit(15)
    ->get();

foreach ($subCategories as $category) {
    $parent = $category->parent ? $category->parent->name : 'Sin padre';
    echo "{$parent} > {$category->name}: {$category->products_count} productos\n";
}

echo "\n=== MIGRACIÓN COMPLETADA ===\n";
