<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Certifier;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ExternalProductService
{
    public function search(string $query): Collection
    {
        $offResponse = Http::withOptions(['verify' => filter_var(env('HTTP_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN)])
            ->get('https://world.openfoodfacts.org/api/v2/search', [
            'search_terms' => $query,
            'fields' => 'product_name,brands,barcode,image_url',
            'page_size' => 10
        ]);

        $offProducts = $offResponse->json()['products'] ?? [];

        if (empty($offProducts)) {
            return collect();
        }

        $foundProducts = new Collection();

        foreach ($offProducts as $offData) {
            if (empty($offData['barcode'])) {
                continue;
            }

            $barcode = $offData['barcode'];
            $kosherData = $this->getKosherStatus($barcode);

            $brandName = $offData['brands'] ?? 'Marca Desconocida';
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($brandName)],
                ['name' => $brandName]
            );

            $product = Product::updateOrCreate(
                ['barcode' => $barcode],
                [
                    'name' => $offData['product_name'] ?? "Producto {$barcode}",
                    'slug' => Str::slug(($offData['product_name'] ?? $barcode) . '-' . $barcode . '-' . Str::random(3)),
                    'kosher_status' => $kosherData['status'],
                    'brand_id' => $brand->id,
                    'certifier_id' => $kosherData['certifier'] ? $kosherData['certifier']->id : null,
                    'image_url' => $offData['image_url'] ?? null,
                    'description' => 'Información sincronizada desde Open Food Facts y OU Kosher.',
                    'source' => 'open_food_facts',
                    'unique_hash' => md5($offData['product_name'] ?? $barcode),
                ]
            );

            $foundProducts->push($product);
        }

        return $foundProducts;
    }

    private function searchOUApi(string $query): Collection
    {
        $foundProducts = new Collection();
        
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36'
            ])
            ->withOptions(['verify' => filter_var(env('HTTP_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN)])
            ->get('https://oukosher.org/wp-json/kosher-api/v1/loc/posts', [
                'query' => $query,
                'limit' => 20,
                'page' => 1,
            ]);

            if ($response->successful()) {
                $results = $response->json()['results'] ?? [];
                
                foreach ($results as $item) {
                    $productName = html_entity_decode($item['LabelName'] ?? '');
                    $brandName = html_entity_decode($item['BrandName'] ?? '');
                    
                    if (!$productName || !$brandName) continue;

                    $brand = Brand::firstOrCreate(
                        ['slug' => Str::slug($brandName)],
                        ['name' => $brandName]
                    );

                    $certifier = Certifier::firstOrCreate(
                        ['slug' => 'ou'],
                        ['name' => 'Orthodox Union', 'logo_symbol' => 'U']
                    );

                    $symbol = strtolower($item['Symbol'] ?? '');
                    $status = 'OU Kosher';
                    if (str_contains($symbol, 'd')) {
                        $status = 'OU Dairy';
                    } elseif (str_contains($symbol, 'pareve') || str_contains($symbol, 'p')) {
                        $status = 'OU Pareve';
                    } elseif (str_contains($symbol, 'fish')) {
                        $status = 'OU Fish';
                    }

                    $product = Product::updateOrCreate(
                        [
                            'name' => $productName, 
                            'brand_id' => $brand->id,
                            'source' => 'ou_api'
                        ],
                        [
                            'slug' => Str::slug($productName . '-' . $brandName . '-' . Str::random(3)),
                            'barcode' => null, // OU API usually doesn't provide barcode
                            'kosher_status' => $status,
                            'certifier_id' => $certifier->id,
                            'description' => 'Importado directamente desde OU Kosher API.',
                            'source' => 'ou_api',
                            'unique_hash' => md5($productName . $brandName),
                        ]
                    );
                    
                    $foundProducts->push($product);
                }
            }
        } catch (\Exception $e) {
            // Log error or just return empty
        }

        return $foundProducts;
    }

    private function getKosherStatus(string $barcode): array
    {
        $response = [
            'status' => 'No Verificado',
            'is_kosher' => false,
            'certifier' => null,
        ];

        // Use the barcode to search on the OU site.
        $ouResponse = Http::withOptions(['verify' => filter_var(env('HTTP_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN)])
            ->get("https://oukosher.org/product-search/", ['fwp_product_search' => $barcode]);
        $html = $ouResponse->body();

        if (str_contains($html, 'product-title')) {
            $response['is_kosher'] = true;
            $response['certifier'] = Certifier::firstOrCreate(
                ['slug' => 'ou'],
                ['name' => 'Orthodox Union', 'logo_symbol' => 'U']
            );

            $lowerHtml = strtolower($html);
            if (str_contains($lowerHtml, 'pareve')) {
                $response['status'] = 'OU Pareve';
            } elseif (str_contains($lowerHtml, 'dairy') || str_contains($lowerHtml, ' d ')) {
                $response['status'] = 'OU Dairy';
            } else {
                $response['status'] = 'OU Kosher';
            }
        }

        return $response;
    }
}
