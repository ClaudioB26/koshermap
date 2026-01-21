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
        $offResponse = Http::get('https://world.openfoodfacts.org/api/v2/search', [
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
                    'description' => 'Información sincronizada desde Open Food Facts y OU Kosher.'
                ]
            );

            $foundProducts->push($product);
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
        $ouResponse = Http::get("https://oukosher.org/product-search/", ['fwp_product_search' => $barcode]);
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
