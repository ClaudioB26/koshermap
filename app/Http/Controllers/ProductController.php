<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\RelatedArticlesService;

class ProductController extends Controller
{
    public function show($slug, RelatedArticlesService $relatedArticlesService)
    {
        // Buscamos el producto por su slug (ej: /product/oreo)
        // Carga también la marca y el certificador para que no haya errores
        // Los productos despublicados (is_active=false) no deben ser accesibles ni por URL directa
        $product = Product::active()->with(['brand', 'certifier', 'category'])->where('slug', $slug)->firstOrFail();

        $relatedArticles = $relatedArticlesService->forProduct($product);

        return view('products.show', compact('product', 'relatedArticles'));
    }
}
