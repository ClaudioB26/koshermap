<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Certifier;
use Illuminate\Support\Str;

class ProcessOUProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $productData)
    {
        $this->productData = $productData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Processing product job...', ['productData' => $this->productData]);

        $productName = $this->productData['name'];
        $brandName = $this->productData['brand'];

        // 1. Find or create the certifier
        $certifier = Certifier::firstOrCreate(
            ['slug' => 'ou'],
            ['name' => 'Orthodox Union', 'logo_symbol' => 'U']
        );
        Log::info('Found or created certifier.', ['certifier' => $certifier]);


        // 2. Search Open Food Facts for barcode and image
        $searchTerm = $productName . ' ' . $brandName;
        Log::info('Searching Open Food Facts...', ['searchTerm' => $searchTerm]);

        $offResponse = Http::get('https://world.openfoodfacts.org/cgi/search.pl', [
            'search_terms' => $searchTerm,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => 1 // We only need the top result
        ]);

        if ($offResponse->failed()) {
            Log::error('Failed to get a response from Open Food Facts API.');
            return;
        }

        $offProducts = $offResponse->json()['products'] ?? [];
        Log::info('Received response from Open Food Facts.', ['product_count' => count($offProducts)]);


        if (empty($offProducts)) {
            Log::warning("No products found on Open Food Facts for: {$searchTerm}");
            return;
        }

        $offProduct = $offProducts[0] ?? null;
        $barcode = $offProduct['code'] ?? null;

        // If we don't get a barcode, we can't reliably create a unique product.
        if (!$barcode) {
            Log::warning("No barcode found on Open Food Facts for: {$productName}");
            return;
        }
        Log::info('Found barcode.', ['barcode' => $barcode]);


        // 3. Find or create the Brand
        $brand = Brand::firstOrCreate(
            ['slug' => Str::slug($brandName)],
            ['name' => $brandName]
        );
        Log::info('Found or created brand.', ['brand' => $brand]);

        // 4. Create or update the product in our database
        $productDataForDb = [
            'name' => $productName,
            'slug' => Str::slug($productName . '-' . $barcode . '-' . Str::random(3)),
            'kosher_status' => $this->productData['status'],
            'brand_id' => $brand->id,
            'certifier_id' => $certifier->id,
            'image_url' => $offProduct['image_url'] ?? null,
            'description' => 'Producto importado y verificado por el scraper de OU Kosher.'
        ];

        Log::info('Attempting to save product to database.', ['data' => $productDataForDb]);


        Product::updateOrCreate(
            ['barcode' => $barcode],
            $productDataForDb
        );

        Log::info("Successfully processed and saved product: {$productName}");
    }
}
