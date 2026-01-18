<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Buscamos el producto por su slug (ej: /product/oreo)
        // Carga también la marca y el certificador para que no haya errores
        $product = Product::with(['brand', 'certifier'])->where('slug', $slug)->firstOrFail();

        return view('products.show', compact('product'));
    }
}


