<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ESTADO DE LA COLA DE JOBS ===\n";

try {
    // Contar jobs pendientes por cola
    $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')
        ->select('queue', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
        ->groupBy('queue')
        ->get();
    
    echo "Jobs pendientes por cola:\n";
    $totalPending = 0;
    foreach ($pendingJobs as $job) {
        echo "- " . $job->queue . ": " . $job->count . " jobs\n";
        $totalPending += $job->count;
    }
    
    echo "\nTotal jobs pendientes: " . $totalPending . "\n";
    
    // Mostrar algunos jobs recientes
    if ($totalPending > 0) {
        echo "\n=== ÚLTIMOS 5 JOBS PENDIENTES ===\n";
        
        $recentJobs = \Illuminate\Support\Facades\DB::table('jobs')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'queue', 'attempts', 'created_at', 'payload']);
        
        foreach ($recentJobs as $job) {
            echo "ID: " . $job->id . " | Queue: " . $job->queue . " | Attempts: " . $job->attempts . " | Created: " . $job->created_at . "\n";
            
            // Decodificar payload para ver el tipo de job
            $payload = json_decode($job->payload, true);
            if (isset($payload['displayName'])) {
                echo "  Job: " . $payload['displayName'] . "\n";
            }
            echo "\n";
        }
    }
    
    // Contar jobs fallidos
    $failedJobs = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();
    echo "\nJobs fallidos totales: " . $failedJobs . "\n";
    
    // Estadísticas de productos procesados
    echo "\n=== ESTADÍSTICAS DE PRODUCTOS ===\n";
    
    $totalProducts = \App\Models\Product::count();
    $ouProducts = \App\Models\Product::where('source', 'like', 'ou_api%')->count();
    $ouIntelligent = \App\Models\Product::where('source', 'ou_api_intelligent')->count();
    $withBarcodes = \App\Models\Product::whereNotNull('barcode')->where('barcode', '!=', '')->count();
    $ouWithBarcodes = \App\Models\Product::where('source', 'ou_api_intelligent')->whereNotNull('barcode')->where('barcode', '!=', '')->count();
    
    echo "Total productos: " . $totalProducts . "\n";
    echo "Productos OU: " . $ouProducts . "\n";
    echo "Productos OU con matching inteligente: " . $ouIntelligent . "\n";
    echo "Productos con barcode: " . $withBarcodes . "\n";
    echo "Productos OU con barcode: " . $ouWithBarcodes . "\n";
    
    if ($ouIntelligent > 0) {
        $successRate = round(($ouWithBarcodes / $ouIntelligent) * 100, 1);
        echo "Tasa de éxito de barcode en OU: " . $successRate . "%\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
