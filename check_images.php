use Illuminate\Support\Facades\Http;

$products = Http::withoutVerifying()->get('https://kosher.org.ar/api/products.php')->json();

foreach(array_slice($products, 0, 20) as $p) {
    if(!empty($p['imagen'])) {
        $url = 'https://kosher.org.ar/images/' . $p['imagen'];
        echo "Checking: $url ... ";
        try {
            $status = Http::withoutVerifying()->timeout(3)->head($url)->status();
            echo $status . "\n";
            if($status == 200) {
                echo "FOUND ONE!\n";
                break;
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "No image for " . $p['descripcion'] . "\n";
    }
}
