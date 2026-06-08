<?php

use Illuminate\Support\Facades\Http;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Fetching https://kosher.org.ar/api/products.php ...\n";
    $response = Http::withoutVerifying()->get('https://kosher.org.ar/api/products.php');
    file_put_contents('ajdut_products.json', $response->body());
    echo "Saved to ajdut_products.json\n";
    
    // Also fetch categories (rubros)
    echo "Fetching https://kosher.org.ar/api/categorias.php ...\n";
    $response = Http::withoutVerifying()->get('https://kosher.org.ar/api/categorias.php');
    file_put_contents('ajdut_categories.json', $response->body());
    echo "Saved to ajdut_categories.json\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
