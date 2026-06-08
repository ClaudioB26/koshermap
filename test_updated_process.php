<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PROBANDO PROCESO ACTUALIZADO ===\n";

// Buscar un producto OU sin barcode para probar
$testProduct = \App\Models\Product::where('source', 'ou_api')
    ->where(function($query) {
        $query->whereNull('barcode')->orWhere('barcode', '');
    })
    ->first();

if ($testProduct) {
    echo "Producto de prueba encontrado:\n";
    echo "ID: " . $testProduct->id . "\n";
    echo "Nombre: " . $testProduct->name . "\n";
    echo "Marca: " . ($testProduct->brand->name ?? 'N/A') . "\n";
    echo "Barcode actual: " . ($testProduct->barcode ?? 'N/A') . "\n";
    echo "Source: " . $testProduct->source . "\n\n";
    
    // Crear datos del job como lo hace el scraper
    $jobData = [
        'name' => $testProduct->name,
        'brand' => $testProduct->brand->name ?? 'Unknown',
        'status' => 'certified'
    ];
    
    echo "Datos del job: " . json_encode($jobData, JSON_PRETTY_PRINT) . "\n";
    
    // Ejecutar el job actualizado
    echo "Ejecutando ProcessOUProduct actualizado...\n";
    
    try {
        $job = new \App\Jobs\ProcessOUProduct($jobData);
        $job->handle();
        echo "Job ejecutado exitosamente\n";
    } catch (Exception $e) {
        echo "Error ejecutando job: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
    
    // Verificar resultados
    echo "\n=== VERIFICANDO RESULTADOS ===\n";
    
    // Recargar el producto desde la BD
    $updatedProduct = \App\Models\Product::find($testProduct->id);
    
    echo "ID: " . $updatedProduct->id . "\n";
    echo "Nombre: " . $updatedProduct->name . "\n";
    echo "Source: " . $updatedProduct->source . "\n";
    echo "Barcode después del proceso: " . ($updatedProduct->barcode ?? 'N/A') . "\n";
    echo "Image URL: " . ($updatedProduct->image_url ?? 'N/A') . "\n";
    
    if ($updatedProduct->barcode) {
        echo "🎉 ¡BARCODE ENCONTRADO: " . $updatedProduct->barcode . "!\n";
    } else {
        echo "❌ No se encontró barcode\n";
    }
    
} else {
    echo "No se encontraron productos OU sin barcode para probar\n";
    
    // Crear uno de ejemplo
    echo "Creando producto de ejemplo...\n";
    
    $brand = \App\Models\Brand::firstOrCreate(['slug' => 'test-brand'], ['name' => 'Test Brand']);
    
    $testProduct = \App\Models\Product::create([
        'name' => 'Test Product for Matching',
        'slug' => 'test-product-for-matching',
        'brand_id' => $brand->id,
        'category' => 'Test',
        'country' => 'USA',
        'description' => 'Test product for matching',
        'kosher_status' => 'certified',
        'certifier_id' => 1,
        'source' => 'ou_api'
    ]);
    
    echo "Producto de prueba creado con ID: " . $testProduct->id . "\n";
    
    // Probar con este producto
    $jobData = [
        'name' => $testProduct->name,
        'brand' => $testProduct->brand->name,
        'status' => 'certified'
    ];
    
    try {
        $job = new \App\Jobs\ProcessOUProduct($jobData);
        $job->handle();
        echo "Job ejecutado exitosamente\n";
        
        $updatedProduct = \App\Models\Product::find($testProduct->id);
        echo "Barcode: " . ($updatedProduct->barcode ?? 'N/A') . "\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
