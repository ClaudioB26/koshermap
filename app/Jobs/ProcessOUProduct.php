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
use App\Models\Country;
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


        // 2. Search Open Food Facts using Intelligent Matching Engine
        Log::info('Using Intelligent Matching Engine for Open Food Facts search...', ['productName' => $productName, 'brandName' => $brandName]);

        // Use the same Intelligent Matching Engine as ProcessOUProductIntelligent
        $intelligentMatchingEngine = new \App\Services\IntelligentMatchingEngine();
        $matchResult = $intelligentMatchingEngine->matchProduct($productName, $brandName);

        Log::info('Intelligent matching result', [
            'status' => $matchResult['status'],
            'confidence' => $matchResult['confidence_score'] ?? 0,
            'barcode' => $matchResult['off_barcode'] ?? 'none'
        ]);

        $offProduct = null;
        $barcode = $matchResult['off_barcode'] ?? null;
        $imageUrl = $matchResult['off_image_url'] ?? null;

        if ($barcode) {
            Log::info('Found barcode via intelligent matching.', ['barcode' => $barcode]);
            // Create a mock OFF product for compatibility
            $offProduct = [
                'code' => $barcode,
                'image_url' => $imageUrl,
                'product_name' => $matchResult['off_product_name'] ?? $productName,
                'categories_tags' => [],
                'countries_tags' => []
            ];
        } else {
            Log::warning("No barcode found via intelligent matching for: {$productName}. Proceeding without barcode.");
            $isPlaceholder = ($productName === 'Nombre Desconocido') || ($brandName === 'Marca Desconocida');
            if ($isPlaceholder) {
                Log::warning('Skipping product due to placeholder name/brand and missing barcode.', ['name' => $productName, 'brand' => $brandName]);
                return;
            }
        }


        // 3. Find or create the Brand
        $brand = Brand::firstOrCreate(
            ['slug' => Str::slug($brandName)],
            ['name' => $brandName]
        );
        Log::info('Found or created brand.', ['brand' => $brand]);

        // 4. Generate Slug (Ensure Uniqueness)
        $matchAttributes = [
            'name' => $productName,
            'brand_id' => $brand->id,
            'source' => 'ou_api'
        ];

        $existingProduct = Product::where($matchAttributes)->first();

        if ($existingProduct) {
            $slug = $existingProduct->slug;
        } else {
            // Generating a new slug for a new record
            if ($barcode) {
                $baseSlug = Str::slug($productName . '-' . $barcode);
            } else {
                $baseSlug = Str::slug($productName . '-' . $brandName);
            }

            // Fallback if slug is empty
            if (empty($baseSlug)) {
                $baseSlug = 'product-' . Str::random(6);
            }

            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        $productDataForDb = [
            'name' => $productName,
            'slug' => $slug,
            'barcode' => $barcode,
            'kosher_status' => $this->productData['status'],
            'brand_id' => $brand->id,
            'certifier_id' => $certifier->id,
            'image_url' => $imageUrl,
            'description' => 'Producto importado y verificado por el scraper de OU Kosher.',
            'source' => 'ou_api',
            'unique_hash' => md5($productName . $brandName . ($barcode ?? '')),
        ];

        // 5. Handle Categories (from OFF)
        $category = null;
        if ($offProduct && !empty($offProduct['categories_tags'])) {
             foreach ($offProduct['categories_tags'] as $catTag) {
                 if (Str::startsWith($catTag, 'en:')) {
                     $catName = Str::title(str_replace('-', ' ', substr($catTag, 3)));
                     $category = Category::firstOrCreate(
                         ['slug' => Str::slug($catName)],
                         ['name' => $catName]
                     );
                     break; 
                 }
             }
        }
        if ($category) {
            $productDataForDb['category_id'] = $category->id;
        }

        // 6. Handle Countries (from OFF or Default)
        $countryIds = [];
        if ($offProduct && !empty($offProduct['countries_tags'])) {
             foreach ($offProduct['countries_tags'] as $countryTag) {
                 if (Str::startsWith($countryTag, 'en:')) {
                     $countryName = Str::title(str_replace('-', ' ', substr($countryTag, 3)));
                     $country = Country::firstOrCreate(
                         ['slug' => Str::slug($countryName)],
                         ['name' => $countryName]
                     );
                     $countryIds[] = $country->id;
                 }
             }
        }
        
        if (empty($countryIds)) {
             // Default to US if not found (for OU products)
             $us = Country::firstOrCreate(['slug' => 'united-states'], ['name' => 'United States', 'code' => 'US']);
             $countryIds[] = $us->id;
        }

        $product = Product::updateOrCreate(
            $matchAttributes,
            $productDataForDb
        );

        $product->countries()->syncWithoutDetaching($countryIds);

        Log::info('Attempting to save product to database.', ['data' => $productDataForDb]);

        Log::info("Successfully processed and saved product: {$productName}");
    }
}
