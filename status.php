<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Limpiar logs "running" huérfanos (runs abortados)
\App\Models\PlaceScrapingLog::where('status', 'running')->update(['status' => 'failed', 'error_message' => 'Abortado']);

echo "Kosher places: " . \App\Models\KosherPlace::count() . "\n\n";
echo "Logs:\n";
foreach (\App\Models\PlaceScrapingLog::orderBy('id','desc')->take(5)->get() as $l) {
    echo "  [{$l->id}] {$l->status} | found:{$l->places_found} created:{$l->places_created} | " . substr($l->error_message ?? '', 0, 60) . "\n";
}
