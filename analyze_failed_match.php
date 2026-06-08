<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ANÁLISIS DE CASOS FALLIDOS ===\n";

// Obtener productos OU procesados que no tienen barcode
$failedProducts = \App\Models\Product::where('source', 'ou_api_intelligent')
    ->where(function($query) {
        $query->whereNull('barcode')->orWhere('barcode', '');
    })
    ->take(5)
    ->get();

echo "Productos OU con matching inteligente pero sin barcode:\n\n";

foreach ($failedProducts as $i => $product) {
    echo "=== CASO " . ($i + 1) . ": " . $product->name . " ===\n";
    echo "ID: " . $product->id . "\n";
    echo "Marca: " . ($product->brand->name ?? 'N/A') . "\n";
    echo "Categoría: " . ($product->category ?? 'N/A') . "\n";
    echo "País: " . ($product->country ?? 'N/A') . "\n";
    echo "Barcode: " . ($product->barcode ?? 'N/A') . "\n";
    
    // Buscar en los logs el resultado del matching para este producto
    echo "\n--- ANALIZANDO MATCHING ---\n";
    
    require_once 'app/Services/IntelligentMatchingEngine.php';
    
    try {
        $matchingEngine = new \App\Services\IntelligentMatchingEngine();
        $matchResult = $matchingEngine->matchProduct($product->name, $product->brand->name ?? '');
        
        echo "Status del matching: " . $matchResult['status'] . "\n";
        echo "Confianza: " . ($matchResult['confidence_score'] ?? 0) . "%\n";
        echo "Barcode encontrado: " . ($matchResult['off_barcode'] ?? 'N/A') . "\n";
        
        // Mostrar variaciones de búsqueda que intentó
        if (isset($matchResult['debug_info']['search_variations'])) {
            echo "Variaciones de búsqueda:\n";
            foreach ($matchResult['debug_info']['search_variations'] as $j => $variation) {
                echo "  " . ($j + 1) . ". '" . $variation['query'] . "' (Precisión: " . $variation['precision'] . ")\n";
            }
        }
        
        // Mostrar candidatos encontrados
        if (isset($matchResult['candidates']) && !empty($matchResult['candidates'])) {
            echo "\nCandidatos encontrados (" . count($matchResult['candidates']) . "):\n";
            foreach ($matchResult['candidates'] as $j => $candidate) {
                echo "  " . ($j + 1) . ". " . ($candidate['product_name'] ?? 'N/A') . "\n";
                echo "     Marca: " . ($candidate['brands'] ?? 'N/A') . "\n";
                echo "     Barcode: " . ($candidate['code'] ?? 'N/A') . "\n";
                echo "     Score: " . ($candidate['confidence_score']['total'] ?? 0) . "\n\n";
            }
        } else {
            echo "\n❌ NO se encontraron candidatos en OFF\n";
            
            // Probar búsquedas manuales para este producto
            echo "\n--- PRUEBAS MANUALES ---\n";
            
            $authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');
            
            // Probar diferentes términos de búsqueda
            $searchTerms = [
                $product->name,
                $product->brand->name ?? '',
                substr($product->name, 0, 20), // Primeras 20 letras
                explode(' ', $product->name)[0] // Primera palabra
            ];
            
            foreach ($searchTerms as $term) {
                if (empty($term)) continue;
                
                echo "Buscando manualmente: '$term'\n";
                
                $response = \Illuminate\Support\Facades\Http::timeout(10)
                    ->withOptions(['verify' => false])
                    ->withHeaders([
                        'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
                        'Accept' => 'application/json',
                        'Authorization' => 'Basic ' . $authToken
                    ])
                    ->get('https://world.openfoodfacts.org/cgi/search.pl', [
                        'search_terms' => $term,
                        'search_simple' => 1,
                        'action' => 'process',
                        'json' => 1,
                        'page_size' => 3
                    ]);
                
                echo "  Status: " . $response->status() . "\n";
                
                if ($response->successful()) {
                    $data = $response->json();
                    $products = $data['products'] ?? [];
                    echo "  Resultados: " . count($products) . "\n";
                    
                    if (!empty($products)) {
                        foreach ($products as $k => $p) {
                            echo "    🎯 " . ($p['product_name'] ?? 'N/A') . " (" . ($p['brands'] ?? 'N/A') . ") Barcode: " . ($p['code'] ?? 'N/A') . "\n";
                        }
                    }
                } else {
                    echo "  Error: " . substr($response->body(), 0, 50) . "...\n";
                }
                
                echo "\n";
                sleep(1);
            }
        }
        
    } catch (Exception $e) {
        echo "Error en matching: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n\n";
}

echo "=== ANÁLISIS COMPLETADO ===\n";
