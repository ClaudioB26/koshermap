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
use App\Services\RelatedArticlesService;

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

    public function country(Request $request, $slug, RelatedArticlesService $relatedArticlesService)
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

        $relatedArticles = $relatedArticlesService->forCountry();

        return view('catalog.countries.show', compact(
            'country', 'products', 'certifiers', 'places', 'placeTypes', 'placeType', 'relatedArticles'
        ));
    }

    public function certifiers()
    {
        // Solo mostrar certificadoras aprobadas que tengan productos.
        // Orden: pro > destacada > free (ver Certifier::TIER_ORDER), alfabetico dentro de cada nivel.
        $certifiers = Certifier::approved()
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get()
            ->sortBy(fn ($c) => \App\Models\Certifier::TIER_ORDER[$c->tier] ?? 99)
            ->values();
        return view('catalog.certifiers.index', compact('certifiers'));
    }

    public function certifier(Request $request, $slug, RelatedArticlesService $relatedArticlesService)
    {
        $certifier = Certifier::where('slug', $slug)->approved()->firstOrFail();

        // El listado paginado de productos por certificadora se saco: eran
        // cientos de tarjetas genericas paginadas (ej. BDK Brasil: 1.167
        // productos en 59 paginas), el mismo patron de contenido templado que
        // se retiro de /product y /brands, solo que anidado aca. Se reemplaza
        // por un conteo + link al buscador (/productos?certifier=), que ya
        // esta en noindex. Ver doc/plan-adsense.md.
        $productsCount = $certifier->products()->active()->count();

        $relatedArticles = $relatedArticlesService->forCertifier();

        return view('catalog.certifiers.show', compact('certifier', 'productsCount', 'relatedArticles'));
    }

    public function brands()
    {
        // Sin slug no se puede construir la URL de la ficha y route() tira 500:
        // el listado se rompia entero por una sola marca mal cargada.
        $brands = Brand::withCount('products')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('products_count', 'desc')
            ->orderBy('name')
            ->paginate(24);

        return view('catalog.brands.index', compact('brands'));
    }

    public function brand($slug, RelatedArticlesService $relatedArticlesService)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $products = $brand->products()
            ->active()
            ->with(['category', 'certifier'])
            ->orderBy('name')
            ->paginate(20);

        $relatedArticles = $relatedArticlesService->forBrand($brand);

        return view('catalog.brands.show', compact('brand', 'products', 'relatedArticles'));
    }

    public function placesIndex(Request $request, RelatedArticlesService $relatedArticlesService)
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

        // Premium primero en todo el listado; destacada_rubro sube dentro de su
        // propio place_type (queda arriba cuando se filtra por ese rubro); gratis
        // al final. Dentro de cada nivel, se ordena por rating como antes.
        $places = $placesQuery
            ->orderByRaw("FIELD(tier, 'premium', 'destacada_rubro', 'free')")
            ->orderBy('google_rating', 'desc')
            ->paginate(24)
            ->withQueryString();

        $relatedArticles = $relatedArticlesService->forPlaces();

        return view('places.index', compact(
            'places', 'countries', 'placeTypes', 'placeType', 'query', 'countrySlug', 'orientation', 'relatedArticles'
        ));
    }
}
