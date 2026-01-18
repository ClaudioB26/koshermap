<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ExternalProductService;

class SearchController extends Controller
{
    public function index(Request $request, ExternalProductService $externalService)
	{
		$query = $request->input('query');
		if (!$query) return view('welcome');

		// 1. Buscamos en nuestra base de datos local
		$products = Product::with('brand')
					->where('barcode', $query)
					->orWhere('name', 'LIKE', "%{$query}%")
					->get();

		// 2. Si no hay nada y es numérico, intentamos importar
		if ($products->isEmpty() && is_numeric($query)) {
			
			// Intentamos la importación normal
			$newProduct = $externalService->searchAndImport($query);

			// Si falló (como pasó recién), intentamos quitarle los ceros a la izquierda
			if (!$newProduct) {
				$cleanQuery = ltrim($query, '0'); 
				$newProduct = $externalService->searchAndImport($cleanQuery);
			}

			if ($newProduct) {
				return redirect()->route('products.show', $newProduct->slug);
			}
		}

		return view('welcome', compact('products', 'query'));
	}
}