<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PROBANDO SCHEMA SERVICE ===\n";

require_once 'app/Services/SchemaService.php';

// Obtener un producto de ejemplo
$product = \App\Models\Product::with(['brand', 'category', 'certifier', 'countries'])
    ->whereNotNull('barcode')
    ->first();

if ($product) {
    echo "=== PRODUCT SCHEMA ===\n";
    $productSchema = \App\Services\SchemaService::productSchema($product);
    echo \App\Services\SchemaService::render($productSchema);
    echo "\n\n";
}

echo "=== ORGANIZATION SCHEMA ===\n";
$orgSchema = \App\Services\SchemaService::organizationSchema();
echo \App\Services\SchemaService::render($orgSchema);
echo "\n\n";

echo "=== WEBSITE SCHEMA ===\n";
$webSchema = \App\Services\SchemaService::webSiteSchema();
echo \App\Services\SchemaService::render($webSchema);
echo "\n\n";

echo "=== BREADCRUMB SCHEMA ===\n";
$breadcrumbs = [
    ['name' => 'Home', 'url' => config('app.url')],
    ['name' => 'Products', 'url' => config('app.url') . '/products'],
    ['name' => $product->name ?? 'Sample Product', 'url' => config('app.url') . '/products/sample']
];
$breadcrumbSchema = \App\Services\SchemaService::breadcrumbSchema($breadcrumbs);
echo \App\Services\SchemaService::render($breadcrumbSchema);
echo "\n\n";

if ($product && $product->certifier) {
    echo "=== CERTIFIER SCHEMA ===\n";
    $certifierSchema = \App\Services\SchemaService::certifierSchema($product->certifier);
    echo \App\Services\SchemaService::render($certifierSchema);
    echo "\n\n";
}

echo "=== HOW-TO SCHEMA ===\n";
$howToSchema = \App\Services\SchemaService::howToSchema();
echo \App\Services\SchemaService::render($howToSchema);
echo "\n\n";

echo "=== FAQ SCHEMA ===\n";
$faqs = [
    [
        'question' => 'What does kosher mean?',
        'answer' => 'Kosher refers to food that complies with Jewish dietary laws.'
    ],
    [
        'question' => 'How do I verify kosher certification?',
        'answer' => 'Look for kosher symbols on packaging and verify them in our database.'
    ]
];
$faqSchema = \App\Services\SchemaService::faqSchema($faqs);
echo \App\Services\SchemaService::render($faqSchema);
echo "\n\n";

echo "=== ANÁLISIS COMPLETADO ===\n";
