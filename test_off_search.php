<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Obtener un producto OU para probar
$product = App\Models\Product::where('source', 'ou_api')->orWhere('source', 'ou_api_intelligent')->first();

if (!$product) {
    echo "No se encontraron productos OU\n";
    exit;
}

echo "=== PRODUCTO DE PRUEBA ===\n";
echo "Nombre: " . $product->name . "\n";
echo "Brand ID: " . $product->brand_id . "\n";
echo "Source: " . $product->source . "\n";

// Obtener nombre de la marca
$brand = App\Models\Brand::find($product->brand_id);
$brandName = $brand ? $brand->name : 'Unknown';
echo "Brand Name: " . $brandName . "\n";

echo "\n=== BÚSQUEDAS EN OPEN FOOD FACTS ===\n";

// Probar diferentes variaciones de búsqueda
$searchTerms = [
    'Coca Cola', // Producto muy común que debe existir
    'Oreo', // Otro producto común
    $brandName, // Solo la marca
    'Montgomery Laboratories' // Nombre completo del laboratorio
];

foreach ($searchTerms as $index => $term) {
    echo "\n--- BÚSQUEDA " . ($index + 1) . ": '$term' ---\n";
    
    $params = [
        'search_terms' => $term,
        'search_simple' => 1,
        'action' => 'process',
        'json' => 1,
        'page_size' => 5
    ];
    
    $url = 'https://world.openfoodfacts.org/cgi/search.pl?' . http_build_query($params);
    echo "URL: " . $url . "\n";
    
    $response = \Illuminate\Support\Facades\Http::timeout(15)
        ->withOptions(['verify' => false])
        ->withHeaders([
            'User-Agent' => 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)',
            'Accept' => 'application/json',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Accept-Encoding' => 'gzip, deflate',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'max-age=0',
            'Referer' => 'https://world.openfoodfacts.org/'
        ])
        ->get($url);
    
    echo "Status Code: " . $response->status() . "\n";
    echo "Content-Type: " . ($response->header('Content-Type') ?? 'N/A') . "\n";
    
    if ($response->failed()) {
        echo "ERROR: " . $response->body() . "\n";
        continue;
    }
    
    $body = $response->body();
    
    // Verificar si es JSON o HTML (rate limiting)
    if (strpos($body, '{') === 0) {
        // Es JSON
        $data = $response->json();
        $products = $data['products'] ?? [];
        echo "Resultados encontrados: " . count($products) . "\n";
        
        if (!empty($products)) {
            foreach ($products as $i => $offProduct) {
                echo "\n  Producto " . ($i + 1) . ":\n";
                echo "    Nombre: " . ($offProduct['product_name'] ?? 'N/A') . "\n";
                echo "    Marca: " . ($offProduct['brands'] ?? 'N/A') . "\n";
                echo "    Barcode: " . ($offProduct['code'] ?? 'N/A') . "\n";
                echo "    Imagen: " . ($offProduct['image_url'] ?? 'N/A') . "\n";
            }
        }
    } else {
        // Es HTML - probablemente rate limiting
        echo "RESPUESTA HTML (Rate Limiting o Error):\n";
        echo "Primeros 200 caracteres: " . substr($body, 0, 200) . "...\n";
        
        // Buscar mensajes de error comunes
        if (strpos($body, 'rate limit') !== false || strpos($body, 'too many') !== false) {
            echo "DETECTADO: Rate Limiting\n";
        } elseif (strpos($body, 'temporarily') !== false || strpos($body, 'unavailable') !== false) {
            echo "DETECTADO: Servicio temporalmente no disponible\n";
        }
    }
    
    // Esperar 3 segundos entre requests para evitar bloqueo
    echo "Esperando 3 segundos...\n";
    sleep(3);
}

echo "\n=== FIN ===\n";
