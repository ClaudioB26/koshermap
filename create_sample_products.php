<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREANDO PRODUCTOS DE MUESTRA PARA CERTIFICADORAS ===\n\n";

$sampleProducts = [
    'kehila' => [
        ['name' => 'Pan Matzá Kehila', 'brand' => 'Kehila', 'description' => 'Pan matzá certificado por Kehila Uruguay'],
        ['name' => 'Vino Kosher Kehila', 'brand' => 'Kehila', 'description' => 'Vino kosher para Shabat'],
        ['name' => 'Aceite de Oliva Kehila', 'brand' => 'Kehila', 'description' => 'Aceite de oliva extra virgen kosher'],
        ['name' => 'Sal Kosher Kehila', 'brand' => 'Kehila', 'description' => 'Sal kosher certificada'],
        ['name' => 'Galletas Kehila', 'brand' => 'Kehila', 'description' => 'Galletas kosher para merienda']
    ],
    'bdk-brasil' => [
        ['name' => 'Pão de Forma BDK', 'brand' => 'BDK Brasil', 'description' => 'Pão de forma kosher certificado'],
        ['name' => 'Queijo Minas BDK', 'brand' => 'BDK Brasil', 'description' => 'Queijo minas kosher'],
        ['name' => 'Café BDK', 'brand' => 'BDK Brasil', 'description' => 'Café torrado kosher'],
        ['name' => 'Biscoitos BDK', 'brand' => 'BDK Brasil', 'description' => 'Biscoitos kosher'],
        ['name' => 'Suco de Laranja BDK', 'brand' => 'BDK Brasil', 'description' => 'Suco de laranja natural kosher']
    ],
    'kosher-chile' => [
        ['name' => 'Vino Tinto Chile Kosher', 'brand' => 'Chile Kosher', 'description' => 'Vino tinto chileno kosher'],
        ['name' => 'Mermelada Chile Kosher', 'brand' => 'Chile Kosher', 'description' => 'Mermelada de frutas kosher'],
        ['name' => 'Aceite de Oliva Chile Kosher', 'brand' => 'Chile Kosher', 'description' => 'Aceite de oliva chileno kosher'],
        ['name' => 'Chocolates Chile Kosher', 'brand' => 'Chile Kosher', 'description' => 'Chocolates kosher premium'],
        ['name' => 'Té Chile Kosher', 'brand' => 'Chile Kosher', 'description' => 'Té verde kosher']
    ],
    'uk-kosher-latam' => [
        ['name' => 'Galletas UK Kosher', 'brand' => 'UK Kosher', 'description' => 'Galletas kosher para toda la familia'],
        ['name' => 'Cereal UK Kosher', 'brand' => 'UK Kosher', 'description' => 'Cereal matutino kosher'],
        ['name' => 'Barra de Chocolate UK Kosher', 'brand' => 'UK Kosher', 'description' => 'Barra de chocolate kosher'],
        ['name' => 'Yogurt UK Kosher', 'brand' => 'UK Kosher', 'description' => 'Yogurt natural kosher'],
        ['name' => 'Pan Integral UK Kosher', 'brand' => 'UK Kosher', 'description' => 'Pan integral kosher']
    ]
];

$totalCreated = 0;

foreach ($sampleProducts as $certifierSlug => $products) {
    echo "Procesando certificadora: {$certifierSlug}\n";
    
    $certifier = App\Models\Certifier::where('slug', $certifierSlug)->first();
    if (!$certifier) {
        echo "  Certificadora no encontrada, omitiendo...\n";
        continue;
    }
    
    foreach ($products as $productData) {
        // Crear o obtener marca
        $brand = App\Models\Brand::firstOrCreate([
            'slug' => Str::slug($productData['brand'])
        ], [
            'name' => $productData['brand'],
            'description' => "Marca de productos kosher certificados por {$certifier->name}"
        ]);
        
        // Generar slug único
        $baseSlug = Str::slug($productData['name'] . '-' . $brand->slug);
        $slug = $baseSlug;
        $counter = 1;
        
        while (App\Models\Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        // Crear producto
        $product = App\Models\Product::firstOrCreate([
            'name' => $productData['name'],
            'brand_id' => $brand->id,
            'certifier_id' => $certifier->id
        ], [
            'slug' => $slug,
            'kosher_status' => 'Certificado',
            'description' => $productData['description'],
            'image_url' => null,
            'source' => 'sample_data',
            'unique_hash' => md5($productData['name'] . $brand->id . $certifier->id)
        ]);
        
        if ($product->wasRecentlyCreated) {
            echo "  Creado: {$productData['name']}\n";
            $totalCreated++;
        } else {
            echo "  Ya existe: {$productData['name']}\n";
        }
    }
    
    echo "  Total productos: " . $certifier->products()->count() . "\n\n";
}

echo "=== RESUMEN ===\n";
echo "Total productos creados: {$totalCreated}\n";

echo "\n=== VERIFICANDO CERTIFICADORAS VISIBLES ===\n";
$visibleCertifiers = App\Models\Certifier::withCount('products')
    ->having('products_count', '>', 0)
    ->orderBy('name')
    ->get();

echo "Certificadoras visibles: " . $visibleCertifiers->count() . "\n";
foreach ($visibleCertifiers as $certifier) {
    echo "- {$certifier->name}: {$certifier->products_count} productos\n";
}

echo "\n¡Ahora visita http://kosherstatus.test/certifiers para ver todas las certificadoras!\n";
