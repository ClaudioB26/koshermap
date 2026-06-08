<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PROCESANDO Kedem Grape Juice Red ===\n";

// Buscar si el producto ya existe
$existingProduct = \App\Models\Product::where('name', 'Kedem Grape Juice Red')->first();

if ($existingProduct) {
    echo "Producto ya existe con ID: " . $existingProduct->id . "\n";
    echo "Source actual: " . $existingProduct->source . "\n";
    echo "Barcode actual: " . ($existingProduct->barcode ?? 'N/A') . "\n";
    
    // Crear un nuevo job para este producto específico
    $jobData = [
        'name' => 'Kedem Grape Juice Red',
        'brand' => 'Kedem',
        'category' => $existingProduct->category ?? 'Beverages',
        'country' => $existingProduct->country ?? 'USA',
        'description' => $existingProduct->description ?? 'Kedem Grape Juice Red',
        'image_url' => $existingProduct->image_url ?? null,
        'certifier_id' => $existingProduct->certifier_id ?? 1,
        'ou_url' => $existingProduct->ou_url ?? null,
        'source' => 'ou_api_intelligent'
    ];
    
    echo "\n=== DESPACHANDO JOB INTELIGENTE ===\n";
    \App\Jobs\ProcessOUProductIntelligent::dispatch($jobData);
    
    echo "Job despachado. Ejecutando...\n";
    
    // Ejecutar el job inmediatamente
    \Illuminate\Support\Facades\Artisan::call('queue:work', [
        '--once' => true,
        '--queue' => 'scraping'
    ]);
    
    echo "\n=== VERIFICANDO RESULTADOS ===\n";
    
    // Esperar un momento y verificar
    sleep(2);
    
    // Buscar el producto actualizado
    $updatedProduct = \App\Models\Product::find($existingProduct->id);
    
    echo "ID: " . $updatedProduct->id . "\n";
    echo "Nombre: " . $updatedProduct->name . "\n";
    echo "Source: " . $updatedProduct->source . "\n";
    echo "Barcode: " . ($updatedProduct->barcode ?? 'N/A') . "\n";
    echo "Image URL: " . ($updatedProduct->image_url ?? 'N/A') . "\n";
    
    if ($updatedProduct->barcode) {
        echo "🎉 ¡BARCODE ENCONTRADO Y GUARDADO!\n";
    } else {
        echo "❌ No se guardó barcode\n";
    }
    
} else {
    echo "Producto no encontrado. Creando uno nuevo...\n";
    
    // Crear el producto primero
    $brand = \App\Models\Brand::where('name', 'Kedem')->first();
    if (!$brand) {
        $brand = \App\Models\Brand::create([
            'name' => 'Kedem',
            'slug' => 'kedem',
            'description' => 'Kedem - Kosher food products'
        ]);
        echo "Marca Kedem creada con ID: " . $brand->id . "\n";
    }
    
    $product = \App\Models\Product::create([
        'name' => 'Kedem Grape Juice Red',
        'slug' => 'kedem-grape-juice-red',
        'brand_id' => $brand->id,
        'category' => 'Beverages',
        'country' => 'USA',
        'description' => 'Kedem Grape Juice Red',
        'certifier_id' => 1,
        'kosher_status' => 'certified', // Agregando el campo faltante
        'source' => 'ou_api'
    ]);
    
    echo "Producto creado con ID: " . $product->id . "\n";
    
    // Despachar job inteligente
    $jobData = [
        'name' => 'Kedem Grape Juice Red',
        'brand' => 'Kedem',
        'category' => 'Beverages',
        'country' => 'USA',
        'description' => 'Kedem Grape Juice Red',
        'certifier_id' => 1,
        'ou_url' => null,
        'source' => 'ou_api_intelligent'
    ];
    
    \App\Jobs\ProcessOUProductIntelligent::dispatch($jobData);
    
    echo "Job despachado. Ejecutando...\n";
    
    // Ejecutar el job
    \Illuminate\Support\Facades\Artisan::call('queue:work', [
        '--once' => true,
        '--queue' => 'scraping'
    ]);
    
    // Verificar resultados
    sleep(2);
    
    $updatedProduct = \App\Models\Product::find($product->id);
    echo "\n=== RESULTADOS ===\n";
    echo "Barcode: " . ($updatedProduct->barcode ?? 'N/A') . "\n";
    echo "Source: " . $updatedProduct->source . "\n";
}

echo "\n=== REVISANDO LOGS ===\n";
$logOutput = shell_exec('Get-Content -Tail 20 storage/logs/laravel.log | Select-String "Kedem|OFF Search results|barcode"');
echo $logOutput;

echo "\n=== ANÁLISIS COMPLETADO ===\n";
