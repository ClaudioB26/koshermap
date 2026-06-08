<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\Product::where('name', 'LIKE', '"%')->count();
echo "Products starting with quote: " . $count . "\n";

$products = \App\Models\Product::where('name', 'LIKE', '"%')->limit(5)->get();
foreach($products as $p) {
    echo " - " . $p->name . "\n";
}
