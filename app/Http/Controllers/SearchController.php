<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ExternalProductService;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function index(Request $request, ExternalProductService $externalService)
    {
        $query = $request->input('query');

        if (!$query) {
            return view('welcome');
        }

        // 1. Search our local database first.
        $localProducts = Product::with(['brand', 'certifier'])
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('barcode', $query)
            ->get();

        // 2. Call the unified external service.
        $externalProducts = $externalService->search($query);

        // 3. Merge local and external results, ensuring uniqueness.
        $allProducts = $localProducts->concat($externalProducts)->unique('barcode');

        return view('welcome', [
            'products' => $allProducts,
            'query' => $query,
        ]);
    }
}
