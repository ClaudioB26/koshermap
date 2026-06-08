<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE MIGRACIÓN DE CATEGORÍAS ===\n";

require_once 'app/Services/CategoryMigrationService.php';

$service = new \App\Services\CategoryMigrationService();

// 1. Generar reporte de categorías actuales
echo "\n1. Generando reporte de categorías actuales...\n";
$categories = $service->generateMigrationReport();

// 2. Ejecutar migración en modo dry-run
echo "\n2. Ejecutando migración en modo dry-run...\n";
$result = $service->migrateAllProducts(true);

echo "\n=== RESULTADO DEL DRY-RUN ===\n";
echo "Productos que se migrarían: {$result['migrated']}\n";
echo "No encontrados: {$result['not_found']}\n";

// 3. Mostrar algunos ejemplos de productos y sus categorías
echo "\n3. Ejemplos de productos actuales:\n";
$products = \App\Models\Product::with('category')->limit(10)->get();

foreach ($products as $product) {
    $categoryName = $product->category ? $product->category->name : 'Sin categoría';
    echo "  - {$product->name} (Categoría: {$categoryName})\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";
