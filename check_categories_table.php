<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ESTRUCTURA DE LA TABLA CATEGORIES ===\n";

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('categories');

echo "Columnas encontradas:\n";
foreach ($columns as $column) {
    echo "  - {$column}\n";
}

echo "\n=== ESTRUCTURA COMPLETA ===\n";
$table = \Illuminate\Support\Facades\Schema::getTable('categories');
print_r($table);

echo "\n=== DATOS EXISTENTES ===\n";
$categories = \App\Models\Category::limit(3)->get();
foreach ($categories as $cat) {
    echo "ID: {$cat->id}, Name: {$cat->name}, Slug: {$cat->slug}\n";
}
