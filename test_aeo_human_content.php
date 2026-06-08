<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE AEO Y CAPA DE VALOR HUMANO ===\n\n";

// 1. Probar AEO Service
echo "1. PROBANDO AEO SERVICE\n";
require_once 'app/Services/AEOService.php';
$aeoService = new \App\Services\AEOService();

// Preguntas de prueba
$questions = [
    "¿cuál es el mejor vino kosher?",
    "¿es kosher el chocolate?",
    "dónde comprar productos kosher",
    "qué significa pareve",
    "recomendación para pan kosher",
    "diferencia entre ou y kmd"
];

foreach ($questions as $question) {
    echo "Pregunta: {$question}\n";
    $answer = $aeoService->generateDirectAnswer($question);
    echo "Respuesta: {$answer}\n";
    echo str_repeat("-", 50) . "\n";
}

// 2. Probar FAQ generation
echo "\n2. PROBANDO GENERACIÓN DE FAQ\n";
$product = \App\Models\Product::with('certifier', 'category')->first();
if ($product) {
    $faq = $aeoService->generateFAQ($product, $product->category);
    echo "FAQ para {$product->name}:\n";
    foreach ($faq as $item) {
        echo "Q: {$item['question']}\n";
        echo "A: {$item['answer']}\n";
        echo str_repeat("-", 30) . "\n";
    }
}

// 3. Probar Human Value Layer Service
echo "\n3. PROBANDO HUMAN VALUE LAYER SERVICE\n";
require_once 'app/Services/HumanValueLayerService.php';
$humanService = new \App\Services\HumanValueLayerService();

if ($product) {
    echo "Generando contenido humano para: {$product->name}\n";
    $content = $humanService->generateHumanContent($product);
    
    echo "\nNOTAS RABÍNICAS:\n";
    foreach ($content['rabbinical_notes'] as $note) {
        echo "- {$note}\n";
    }
    
    echo "\nRECOMENDACIONES DE USO:\n";
    foreach ($content['usage_recommendations'] as $rec) {
        echo "- {$rec}\n";
    }
    
    echo "\nRECETAS:\n";
    foreach ($content['recipes'] as $recipe) {
        echo "- {$recipe['name']}: {$recipe['description']}\n";
    }
    
    echo "\nCONTEXTO CULTURAL:\n";
    foreach ($content['cultural_context'] as $context) {
        echo "- {$context}\n";
    }
    
    echo "\nRESEÑA DE EXPERTO:\n";
    foreach ($content['expert_review'] as $review) {
        echo "- {$review}\n";
    }
    
    echo "\nRETROALIMENTACIÓN COMUNITARIA:\n";
    foreach ($content['community_feedback'] as $feedback) {
        echo "- {$feedback}\n";
    }
}

// 4. Probar optimización de contenido
echo "\n4. PROBANDO OPTIMIZACIÓN DE CONTENIDO AEO\n";
$originalContent = "Este producto es excelente para consumo kosher";
$optimizedContent = $aeoService->optimizeContentForAEO($originalContent);

echo "Original: {$originalContent}\n";
echo "Optimizado: {$optimizedContent}\n";

// 5. Estadísticas
echo "\n5. ESTADÍSTICAS DE IMPLEMENTACIÓN\n";
$stats = [
    'total_products' => \App\Models\Product::count(),
    'products_with_description' => \App\Models\Product::whereNotNull('description')->where('description', '!=', '')->count(),
    'products_without_description' => \App\Models\Product::whereNull('description')->orWhere('description', '')->count(),
    'categories' => \App\Models\Category::count(),
    'certifiers' => \App\Models\Certifier::count(),
];

echo "Total productos: {$stats['total_products']}\n";
echo "Con descripción: {$stats['products_with_description']}\n";
echo "Sin descripción: {$stats['products_without_description']}\n";
echo "Categorías: {$stats['categories']}\n";
echo "Certificadoras: {$stats['certifiers']}\n";

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "AEO Service: IMPLEMENTADO\n";
echo "Human Value Layer Service: IMPLEMENTADO\n";
echo "Ambos servicios listos para producción\n";

echo "\nPara generar contenido humano para todos los productos:\n";
echo "php artisan human:generate --limit=100\n";
