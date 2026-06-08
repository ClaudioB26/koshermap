<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FORZANDO JOB INTELIGENTE PARA KEDEM ===\n";

// Buscar el producto Kedem
$product = \App\Models\Product::where('name', 'Kedem Grape Juice Red')->first();

if ($product) {
    echo "Producto encontrado: ID " . $product->id . "\n";
    
    // Actualizar para que se procese con matching inteligente
    $product->source = 'ou_api'; // Asegurar que tenga el source correcto
    $product->save();
    
    echo "Source actualizado a: " . $product->source . "\n";
    
    // Crear datos del job como lo hace el scraper
    $jobData = [
        'name' => $product->name,
        'brand' => $product->brand->name,
        'category' => $product->category,
        'country' => $product->country,
        'description' => $product->description,
        'certifier_id' => $product->certifier_id,
        'ou_url' => $product->ou_url,
        'status' => 'certified', // Agregando el status faltante
        'source' => 'ou_api_intelligent'
    ];
    
    echo "Datos del job: " . json_encode($jobData, JSON_PRETTY_PRINT) . "\n";
    
    // Despachar job inteligente
    \App\Jobs\ProcessOUProductIntelligent::dispatch($jobData);
    
    echo "Job despachado exitosamente\n";
    
    // Ejecutar el job inmediatamente
    echo "Ejecutando job...\n";
    
    try {
        $job = new \App\Jobs\ProcessOUProductIntelligent($jobData);
        $job->handle();
        echo "Job ejecutado manualmente\n";
    } catch (Exception $e) {
        echo "Error ejecutando job: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
    
    // Verificar resultados
    echo "\n=== VERIFICANDO RESULTADOS ===\n";
    
    // Recargar el producto desde la BD
    $updatedProduct = \App\Models\Product::find($product->id);
    
    echo "ID: " . $updatedProduct->id . "\n";
    echo "Nombre: " . $updatedProduct->name . "\n";
    echo "Source: " . $updatedProduct->source . "\n";
    echo "Barcode: " . ($updatedProduct->barcode ?? 'N/A') . "\n";
    echo "Image URL: " . ($updatedProduct->image_url ?? 'N/A') . "\n";
    
    if ($updatedProduct->barcode) {
        echo "🎉 ¡BARCODE GUARDADO: " . $updatedProduct->barcode . "!\n";
    } else {
        echo "❌ No se guardó barcode\n";
    }
    
} else {
    echo "Producto Kedem no encontrado\n";
}

echo "\n=== REVISANDO LOGS RECIENTES ===\n";
$logEntries = file_get_contents('storage/logs/laravel.log');
$lines = explode("\n", $logEntries);
$recentLines = array_slice($lines, -20);

foreach ($recentLines as $line) {
    if (strpos($line, 'Kedem') !== false || strpos($line, 'OFF Search') !== false || strpos($line, 'barcode') !== false) {
        echo $line . "\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
