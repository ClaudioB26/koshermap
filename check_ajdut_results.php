<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESULTADOS AJDUT ===\n";

$ajdutCount = \App\Models\Product::where('source', 'ajdut_ar')->count();
echo "Productos AJDUT: $ajdutCount\n";

echo "\n=== PRODUCTOS RECIENTES ===\n";
$recent = \App\Models\Product::latest()->take(5)->get(['id', 'name', 'barcode', 'source']);

foreach ($recent as $p) {
    echo 'ID: ' . $p->id . ' | Nombre: ' . $p->name . ' | Barcode: ' . ($p->barcode ?? 'N/A') . ' | Source: ' . $p->source . "\n";
}

echo "\n=== BUSCANDO LOGS DE AJDUT ===\n";

// Buscar logs específicos de AJDUT
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    $lines = explode("\n", $content);
    
    $ajdutLogs = [];
    foreach (array_reverse(array_slice($lines, -100)) as $line) {
        if (strpos($line, 'AJDUT') !== false || strpos($line, 'ajdut') !== false) {
            $ajdutLogs[] = $line;
        }
    }
    
    if (!empty($ajdutLogs)) {
        echo "Logs recientes de AJDUT:\n";
        foreach (array_slice($ajdutLogs, 0, 5) as $log) {
            echo "  " . $log . "\n";
        }
    } else {
        echo "No se encontraron logs recientes de AJDUT\n";
    }
}

echo "\n=== ANÁLISIS COMPLETADO ===\n";
