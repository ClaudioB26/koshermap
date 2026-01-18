<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;

class ExternalProductService
{
    public function searchAndImport($barcode)
    {
        // 1. Intentar traer datos e imagen de Open Food Facts
        $offResponse = Http::get("https://world.openfoodfacts.org/api/v2/product/{$barcode}.json");
        $offData = $offResponse->json()['product'] ?? null;

        // 2. Verificación en la web de la OU (Usando el buscador oficial)
        // Simulamos una búsqueda real en su sitio
        $ouResponse = Http::get("https://oukosher.org/product-search/", [
            'fwp_product_search' => $barcode
        ]);
        
        $html = $ouResponse->body();
        $status = 'No Verificado / Pendiente';
        $ouName = null;

        // Buscamos si el HTML contiene indicadores de estatus
        if (str_contains($html, 'product-title')) {
            $ouName = "Producto encontrado en OU";
            
            $lowerHtml = strtolower($html);
            if (str_contains($lowerHtml, 'pareve')) {
                $status = 'OU Parve';
            } elseif (str_contains($lowerHtml, 'dairy') || str_contains($lowerHtml, ' d ')) {
                $status = 'OU Dairy';
            } else {
                $status = 'OU Kosher';
            }
        }

        // Si no hay rastro del producto en ninguna de las dos fuentes, abortamos
        if (!$offData && !$ouName) {
            return null;
        }

        // 3. Preparar datos para la base de datos
        $finalName = $offData['product_name'] ?? ($ouName ? "Producto OU $barcode" : "Producto $barcode");
        $brandName = $offData['brands'] ?? 'Marca Desconocida';

        $brand = Brand::firstOrCreate(
            ['slug' => Str::slug($brandName)],
            ['name' => $brandName]
        );

        // 4. Crear o actualizar el producto
        return Product::updateOrCreate(
            ['barcode' => $barcode],
            [
                'name' => $finalName,
                'slug' => Str::slug($finalName . '-' . $barcode . '-' . Str::random(3)),
                'kosher_status' => $status,
                'brand_id' => $brand->id,
                'image_url' => $offData['image_url'] ?? null,
                'description' => "Información sincronizada automáticamente desde fuentes oficiales."
            ]
        );
    }
}