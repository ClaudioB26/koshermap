<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\RelatedArticlesService;

class ProductController extends Controller
{
    public function show($slug, RelatedArticlesService $relatedArticlesService)
    {
        // Los productos despublicados (is_active=false) no son accesibles ni por URL directa.
        $product = Product::active()->with(['brand', 'certifier', 'category'])->where('slug', $slug)->firstOrFail();

        $relatedArticles = $relatedArticlesService->forProduct($product);

        // Otros productos de la misma marca (si la hay) o de la misma categoría.
        $related = Product::active()
            ->with(['brand', 'certifier'])
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                if ($product->brand_id) {
                    $q->where('brand_id', $product->brand_id);
                } elseif ($product->category_id) {
                    $q->where('category_id', $product->category_id);
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->orderBy('name')
            ->limit(6)
            ->get();

        return view('products.show', compact('product', 'relatedArticles', 'related'));
    }
}