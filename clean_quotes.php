<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Str;

echo "Cleaning products with starting/ending quotes...\n";

// Find products starting with quote
$products = Product::where('name', 'LIKE', '"%')->orWhere('name', 'LIKE', '%"')->get();

$count = 0;
foreach ($products as $product) {
    $originalName = $product->name;
    $newName = trim($originalName, " \t\n\r\0\x0B\"'");
    
    if ($originalName !== $newName) {
        $product->name = $newName;
        // Also update slug if needed, but unique_hash logic might rely on original name?
        // Scraper uses: $uniqueHash = md5($productName . $brandName . ($barcode ?? ''));
        // If we change name, unique_hash won't match future scrapes unless we update it too or if scraper uses cleaned name.
        // Scraper NOW uses cleaned name. So we should probably update unique_hash too if we want consistency, 
        // BUT changing unique_hash might break things if not careful.
        // However, if we don't update unique_hash, the scraper will think it's a new product because it generates hash from cleaned name.
        
        // Let's see how ScrapeOUKosher generates hash.
        // It's actually in ProcessOUProduct job:
        // 'unique_hash' => md5($productName . $brandName . ($barcode ?? '')),
        // And $productName is passed from the command (which is now cleaned).
        
        // So yes, we should ideally update unique_hash too to avoid duplicates on next scrape.
        // But we don't have the brand name handy in this simple loop unless we load relationship or it's on product.
        // Product has brand_id.
        
        // For now, let's just clean the name for display. 
        // If scraper runs again, it might create duplicates if hash is different.
        // Let's try to update hash if possible, or accept that next scrape might duplicate/update.
        // Wait, ProcessOUProduct checks:
        // $existingProduct = Product::where($matchAttributes)->first();
        // $matchAttributes = ['name' => $productName, 'brand_id' => $brand->id, 'source' => 'ou_api'];
        
        // If I update the name here, the next scrape (with cleaned name) will match this product by name!
        // So I DON'T need to update unique_hash for the *matching* logic, but I should update it for consistency if that's used for ID.
        // Actually, updateOrCreate uses unique_hash as the key in ScrapeAjdut/KMD, but ProcessOUProduct uses:
        // Product::updateOrCreate($matchAttributes, $productDataForDb);
        // where $matchAttributes includes 'name'.
        
        // So if I update 'name' here to be the cleaned version, the scraper (sending cleaned version) will find it. Perfect.
        
        $product->save();
        echo "Fixed: [$originalName] -> [$newName]\n";
        $count++;
    }
}

echo "Cleaned $count products.\n";
