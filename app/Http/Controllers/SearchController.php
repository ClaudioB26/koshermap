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
        $countrySlug = $request->input('country');
        
        // Use detected country if no explicit country filter is provided
        if (!$countrySlug && $userCountry = $request->attributes->get('userCountry')) {
            $countrySlug = $userCountry->slug;
        }

        $categorySlug = $request->input('category');
        $certifierSlug = $request->input('certifier');

        if (!$query) {
            // Si hay un país detectado, lo pasamos a la vista welcome
            return view('welcome', [
                'selectedCountry' => $countrySlug
            ]);
        }

        // Clean EAN: remove leading zeros if numeric
        if (is_numeric($query)) {
            $query = ltrim($query, '0');
        }

        // 1. Search our local database first.
        $localQuery = Product::with(['brand', 'certifier', 'countries', 'category']);

        // Apply Context Filters
        if ($countrySlug) {
            $localQuery->where(function($q) use ($countrySlug) {
                // Product is explicitly in country OR Certifier covers country
                $q->whereHas('countries', function($sq) use ($countrySlug) {
                    $sq->where('slug', $countrySlug);
                })->orWhereHas('certifier.countries', function($sq) use ($countrySlug) {
                    $sq->where('slug', $countrySlug);
                });
            });
        }
        if ($categorySlug) {
            $localQuery->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        if ($certifierSlug) {
            $localQuery->whereHas('certifier', function($q) use ($certifierSlug) {
                $q->where('slug', $certifierSlug);
            });
        }

        $localProducts = $localQuery->where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('barcode', $query);
        })->get();

        // 2. Call the unified external service ONLY if no specific country/category context is enforced,
        // OR if we want to fallback. For now, let's include it only if no strict filters are applied,
        // because external API results are not categorized/localized yet.
        $externalProducts = collect();
        if (!$countrySlug && !$categorySlug && !$certifierSlug) {
             $externalProducts = $externalService->search($query);
        }

        // 3. Merge local and external results, ensuring uniqueness.
        $allProducts = $localProducts->concat($externalProducts)->unique(function ($item) {
            return $item->barcode ?? $item->unique_hash;
        });

        return view('welcome', [
            'products' => $allProducts,
            'query' => $query,
            'selectedCountry' => $countrySlug,
            'selectedCategory' => $categorySlug,
            'selectedCertifier' => $certifierSlug,
        ]);
    }
}
