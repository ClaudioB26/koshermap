<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PROBANDO NUEVO PRODUCTO AJDUT ===\n";

// Ejecutar scraper AJDUT con 1 producto para ver si crea jobs
echo "Ejecutando scraper AJDUT con 1 producto...\n";

$output = [];
$return_var = 0;
exec('php artisan scrape:ajdut --limit=1', $output, $return_var);

echo "Output del scraper:\n";
foreach ($output as $line) {
    echo "  " . $line . "\n";
}

echo "Return code: $return_var\n";

// Verificar si se creó un job nuevo
echo "\n=== VERIFICANDO JOBS ===\n";

$jobs = \Illuminate\Support\Facades\DB::table('jobs')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get(['id', 'queue', 'created_at', 'payload']);

foreach ($jobs as $job) {
    echo "Job ID: " . $job->id . " | Queue: " . $job->queue . " | Created: " . $job->created_at . "\n";
    
    $payload = json_decode($job->payload, true);
    if (isset($payload['displayName'])) {
        echo "  Type: " . $payload['displayName'] . "\n";
    }
}

// Procesar el job si existe
echo "\n=== PROCESANDO JOB ===\n";
exec('php artisan queue:work --queue=scraping --max-jobs=1', $output, $return_var);

foreach ($output as $line) {
    echo "  " . $line . "\n";
}

echo "\n=== VERIFICANDO PRODUCTOS NUEVOS ===\n";
$latest = \App\Models\Product::where('source', 'ajdut_ar')
    ->orderBy('id', 'desc')
    ->limit(3)
    ->get(['id', 'name', 'barcode', 'source']);

foreach ($latest as $product) {
    echo "ID: " . $product->id . " | Nombre: " . $product->name . " | Barcode: " . ($product->barcode ?? 'N/A') . " | Source: " . $product->source . "\n";
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
