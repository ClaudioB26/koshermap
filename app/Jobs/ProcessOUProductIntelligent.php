<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Certifier;
use App\Models\Category;
use App\Models\Country;
use App\Services\IntelligentMatchingEngine;
use App\Services\AutoCategorizationService;
use Illuminate\Support\Str;

class ProcessOUProductIntelligent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productData;

    public function __construct(array $productData)
    {
        $this->productData = $productData;
    }

    public function handle()
    {
        Log::info('Starting intelligent product processing', ['productData' => $this->productData]);

        $productName = $this->productData['name'];
        $brandName = $this->productData['brand'];
        $kosherStatus = $this->productData['status'];

        try {
            // 1. Obtener o crear certificador
            $certifier = Certifier::firstOrCreate(
                ['slug' => 'ou'],
                ['name' => 'Orthodox Union', 'logo_symbol' => 'U']
            );

            // 2. Ejecutar matching inteligente con Open Food Facts
            $matchingEngine = new IntelligentMatchingEngine();
            $matchResult = $matchingEngine->matchProduct($productName, $brandName);

            Log::info('Matching result', [
                'status' => $matchResult['status'],
                'confidence' => $matchResult['confidence_score'] ?? 0,
                'barcode' => $matchResult['off_barcode'] ?? 'none'
            ]);

            // 3. Preparar datos del producto
            $barcode = $matchResult['off_barcode'] ?? null;
            $imageUrl = $matchResult['off_image_url'] ?? null;

            // 4. Obtener o crear marca
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($brandName)],
                ['name' => $brandName]
            );

            // 5. Generar slug único
            $slug = $this->generateUniqueSlug($productName, $brand, $barcode);

            // 6. Preparar datos para la base de datos
            $productData = [
                'name' => $productName,
                'slug' => $slug,
                'barcode' => $barcode,
                'kosher_status' => $kosherStatus,
                'brand_id' => $brand->id,
                'certifier_id' => $certifier->id,
                'image_url' => $imageUrl,
                'description' => 'Producto importado y verificado por el scraper de OU Kosher con matching inteligente.',
                'source' => 'ou_api_intelligent',
                'unique_hash' => md5($productName . $brandName . ($barcode ?? '')),
            ];

            // 7. Manejar categorías (si tenemos datos del match)
            if (isset($matchResult['off_categories'])) {
                $category = $this->findOrCreateCategory($matchResult['off_categories']);
                if ($category) {
                    $productData['category_id'] = $category->id;
                }
            }

            // 7.1. Categorización automática si no tiene categoría
            if (!isset($productData['category_id'])) {
                $autoCategorizationService = new AutoCategorizationService(new \App\Services\CategoryMigrationService());
                $tempProduct = new \App\Models\Product([
                    'name' => $productName,
                    'description' => 'Producto importado y verificado por el scraper de OU Kosher con matching inteligente.'
                ]);
                
                $autoCategory = $autoCategorizationService->categorizeProduct($tempProduct);
                if ($autoCategory) {
                    $productData['category_id'] = $autoCategory->id;
                    Log::info('Auto-categorized product', [
                        'product_name' => $productName,
                        'category' => $autoCategory->name,
                        'category_id' => $autoCategory->id
                    ]);
                }
            }

            // 8. Manejar países
            $countryIds = $this->findOrCreateCountries($matchResult['off_countries'] ?? ['US']);

            // 9. Verificar si el producto ya existe (incluso con otro source)
            $existingProduct = Product::where('name', $productName)
                ->where('brand_id', $brand->id)
                ->first();

            if ($existingProduct) {
                // Actualizar producto existente con datos del matching inteligente
                $updateData = [
                    'barcode' => $barcode,
                    'image_url' => $imageUrl,
                    'description' => 'Producto importado y verificado por el scraper de OU Kosher con matching inteligente.',
                    'source' => 'ou_api_intelligent', // Actualizar source
                ];

                if (isset($productData['category_id'])) {
                    $updateData['category_id'] = $productData['category_id'];
                }

                $existingProduct->update($updateData);
                $existingProduct->countries()->syncWithoutDetaching($countryIds);

                Log::info('Updated existing product with intelligent matching', [
                    'product_id' => $existingProduct->id,
                    'product_name' => $existingProduct->name,
                    'barcode_found' => !empty($barcode),
                    'match_status' => $matchResult['status'],
                    'confidence_score' => $matchResult['confidence_score'] ?? 0
                ]);

                return; // Salir, ya procesamos
            }

            // 10. Crear nuevo producto si no existe
            $product = Product::create($productData);
            $product->countries()->syncWithoutDetaching($countryIds);

            // 10. Log de éxito con detalles del matching
            Log::info('Product processed successfully', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'match_status' => $matchResult['status'],
                'confidence_score' => $matchResult['confidence_score'] ?? 0,
                'has_barcode' => !empty($barcode),
                'has_image' => !empty($imageUrl)
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing OU product', [
                'product' => $productName,
                'brand' => $brandName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Opcional: reintentar con el método original como fallback
            $this->fallbackToOriginalMethod();
        }
    }

    private function generateUniqueSlug($productName, $brand, $barcode = null)
    {
        // Verificar si ya existe un slug para este producto
        $existing = Product::where('name', $productName)
            ->where('brand_id', $brand->id)
            ->first();

        if ($existing) {
            return $existing->slug;
        }

        // Generar nuevo slug
        if ($barcode) {
            $baseSlug = Str::slug($productName . '-' . $barcode);
        } else {
            $baseSlug = Str::slug($productName . '-' . $brand->name);
        }

        if (empty($baseSlug)) {
            $baseSlug = 'product-' . Str::random(6);
        }

        $slug = $baseSlug;
        $counter = 1;
        
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function findOrCreateCategory($categories)
    {
        if (empty($categories)) {
            return null;
        }

        foreach ($categories as $category) {
            if (is_string($category) && str_starts_with($category, 'en:')) {
                $catName = Str::title(str_replace('-', ' ', substr($category, 3)));
                return Category::firstOrCreate(
                    ['slug' => Str::slug($catName)],
                    ['name' => $catName]
                );
            }
        }

        return null;
    }

    private function findOrCreateCountries($countries)
    {
        $countryIds = [];

        foreach ($countries as $country) {
            if (is_string($country) && str_starts_with($country, 'en:')) {
                $countryName = Str::title(str_replace('-', ' ', substr($country, 3)));
                $country = Country::firstOrCreate(
                    ['slug' => Str::slug($countryName)],
                    ['name' => $countryName]
                );
                $countryIds[] = $country->id;
            }
        }

        // Default a US si no se encontraron países
        if (empty($countryIds)) {
            $us = Country::firstOrCreate(['slug' => 'united-states'], ['name' => 'United States', 'code' => 'US']);
            $countryIds[] = $us->id;
        }

        return $countryIds;
    }

    private function fallbackToOriginalMethod()
    {
        Log::warning('Falling back to original processing method', [
            'product' => $this->productData['name'],
            'brand' => $this->productData['brand']
        ]);

        // Disparar el job original como fallback
        ProcessOUProduct::dispatch($this->productData)->onQueue('scraping');
    }
}
