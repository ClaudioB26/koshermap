<?php

use Illuminate\Support\Facades\Http;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing OU API...\n";

try {
    $response = Http::withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36'
    ])
    ->withOptions(['verify' => false])
    ->get('https://oukosher.org/wp-json/kosher-api/v1/loc/posts', [
        'query' => 'a',
        'limit' => 5,
        'page' => 1,
    ]);

    echo "Status: " . $response->status() . "\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "Total Results: " . ($data['total'] ?? 'N/A') . "\n";
        echo "Products in this page: " . count($data['results'] ?? []) . "\n";
        if (!empty($data['results'])) {
            echo "First product: " . print_r($data['results'][0], true) . "\n";
        }
    } else {
        echo "Error Body: " . $response->body() . "\n";
    }

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
