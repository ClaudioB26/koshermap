<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Country;
use App\Models\Certifier;
use App\Models\Product;
use App\Models\Brand;
use App\Models\KosherPlace;

class CatalogController extends Controller
{
    public function categories()
    {
        // Fetch top-level categories with their children
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();
            
        // Optional: Sort by translated name in PHP
        $categories = $categories->sortBy('name');
        
        return view('catalog.categories.index', compact('categories'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->with('children')->firstOrFail();

        // Mostrar productos de esta categoría y de todas sus subcategorías (a cualquier profundidad)
        $products = Product::active()
            ->whereIn('category_id', $category->selfAndDescendantIds())
            ->paginate(20);

        return view('catalog.categories.show', compact('category', 'products'));
    }

    public function countries()
    {
        $countries = Country::orderBy('name')->get();
        return view('catalog.countries.index', compact('countries'));
    }

    public function country(Request $request, $slug)
    {
        $country = Country::where('slug', $slug)->firstOrFail();

        // Visitar la página de un país lo convierte en el país preferido del usuario
        Cookie::queue('user_country', $country->slug, 60 * 24 * 365);
        View::share('userCountry', $country);

        $products   = $country->products()->active()->paginate(20);
        $certifiers = $country->certifiers;

        // Lugares kosher en este país
        $placeType = $request->input('place_type');

        $placesQuery = KosherPlace::whereHas('city', fn ($q) => $q->where('country_id', $country->id))
            ->approved()
            ->where('is_active', true)
            ->with('city')
            ->orderBy('google_rating', 'desc');

        if ($placeType) {
            $placesQuery->where('place_type', $placeType);
        }

        $places = $placesQuery->get();

        // Tipos disponibles para los filtros
        $placeTypes = KosherPlace::whereHas('city', fn ($q) => $q->where('country_id', $country->id))
            ->approved()
            ->where('is_active', true)
            ->selectRaw('place_type, count(*) as total')
            ->groupBy('place_type')
            ->orderBy('total', 'desc')
            ->pluck('total', 'place_type');

        return view('catalog.countries.show', compact(
            'country', 'products', 'certifiers', 'places', 'placeTypes', 'placeType'
        ));
    }

    public function certifiers()
    {
        // Solo mostrar certificadoras que tengan productos
        $certifiers = Certifier::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();
        return view('catalog.certifiers.index', compact('certifiers'));
    }

    public function certifier(Request $request, $slug)
    {
        $certifier = Certifier::where('slug', $slug)->firstOrFail();
        
        $categorySlug = $request->input('category');
        
        $productsQuery = $certifier->products()->active()->with('category');
        
        $category = null;
        if ($categorySlug) {
            $productsQuery->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
            $category = Category::where('slug', $categorySlug)->first();
        }
        
        $products = $productsQuery->paginate(20)->withQueryString();

        // Get all categories that have products certified by this certifier
        // We only want categories that actually have products for this certifier
        $categories = Category::whereHas('products', function($q) use ($certifier) {
            $q->where('certifier_id', $certifier->id);
        })->with('parent')->get()->sortBy('name');

        return view('catalog.certifiers.show', compact('certifier', 'products', 'categories', 'category'));
    }
    
    public function brands()
    {
        $brands = Brand::withCount('products')
            ->orderBy('products_count', 'desc')
            ->orderBy('name')
            ->paginate(24);

        return view('catalog.brands.index', compact('brands'));
    }

    public function brand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $products = $brand->products()
            ->active()
            ->with(['category', 'certifier'])
            ->orderBy('name')
            ->paginate(20);

        return view('catalog.brands.show', compact('brand', 'products'));
    }

    public function placesIndex(Request $request)
    {
        $query       = $request->input('query');
        $countrySlug = $request->input('country');
        $placeType   = $request->input('place_type');
        $orientation = $request->input('orientation');

        // Si no se especificó país explícitamente, usar el país detectado/preferido del usuario
        if (!$request->has('country') && $userCountry = $request->attributes->get('userCountry')) {
            $countrySlug = $userCountry->slug;
        }

        $countries = \App\Models\Country::orderBy('name')->get();

        $placesQuery = KosherPlace::approved()
            ->where('is_active', true)
            ->with(['city.country']);

        if ($query) {
            $placesQuery->where('name', 'like', "%{$query}%");
        }

        if ($countrySlug) {
            $placesQuery->whereHas('city.country', fn ($q) => $q->where('slug', $countrySlug));
        }

        // Por defecto solo se muestran sinagogas/comunidades de orientación ortodoxa.
        // Con ?orientation=all se ven todas; con ?orientation=reform, etc. se filtra esa.
        if ($orientation && $orientation !== 'all') {
            $placesQuery->where(function ($q) use ($orientation) {
                $q->whereNotIn('place_type', KosherPlace::ORIENTABLE_TYPES)
                  ->orWhere('orientation', $orientation);
            });
        } elseif (!$orientation) {
            $placesQuery->where(function ($q) {
                $q->whereNotIn('place_type', KosherPlace::ORIENTABLE_TYPES)
                  ->orWhere('orientation', 'orthodox');
            });
        }

        // Conteo por tipo de lugar respetando los filtros activos (país, búsqueda, orientación)
        $placeTypes = (clone $placesQuery)
            ->selectRaw('place_type, count(*) as total')
            ->groupBy('place_type')
            ->orderBy('total', 'desc')
            ->pluck('total', 'place_type');

        if ($placeType) {
            $placesQuery->where('place_type', $placeType);
        }

        $places = $placesQuery->orderBy('google_rating', 'desc')->paginate(24)->withQueryString();

        return view('places.index', compact(
            'places', 'countries', 'placeTypes', 'placeType', 'query', 'countrySlug', 'orientation'
        ));
    }
}
