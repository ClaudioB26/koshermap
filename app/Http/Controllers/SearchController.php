<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ExternalProductService;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public const KOSHER_STATUS_GROUPS = [
        'parve'   => ['Pareve'],
        'lacteo'  => ['Dairy', 'OU Dairy'],
        'carnico' => ['Meat'],
        'pescado' => ['OU Fish'],
        'otro'    => ['OU Kosher', 'certified', 'Certificado', 'Unknown'],
    ];

    public const TIPO_LABELS = [
        'parve'   => 'Parve',
        'lacteo'  => 'Lácteo',
        'carnico' => 'Cárnico',
        'pescado' => 'Pescado',
        'otro'    => 'Sin especificar',
    ];

    private const BRAND_FACET_LIMIT = 10;
    private const CATEGORY_FACET_LIMIT = 12;
    private const PER_PAGE = 24;

    public function index(Request $request, ExternalProductService $externalService)
    {
        $query = $request->input('query');
        $countrySlug = $request->input('country');

        // Use detected country if no explicit country filter is provided
        if (!$request->has('country') && $userCountry = $request->attributes->get('userCountry')) {
            $countrySlug = $userCountry->slug;
        }

        $categorySlug = $request->input('category');
        $certifierSlug = $request->input('certifier');
        $brandSlug = $request->input('brand');
        $tipo = $request->input('tipo');

        $hasAnyFilter = $request->has('country') || $request->has('category')
            || $request->has('certifier') || $request->has('brand') || $request->has('tipo');

        if (!$query && !$request->has('query') && !$hasAnyFilter) {
            // Si hay un país detectado, lo pasamos a la vista welcome
            return view('welcome', [
                'selectedCountry' => $countrySlug
            ]);
        }

        $query = $query ?? '';

        // Clean EAN: remove leading zeros if numeric
        if (is_numeric($query)) {
            $query = ltrim($query, '0');
        }

        // Filtros base, comunes a la búsqueda principal y a todas las facetas
        $applyCountry = function ($q) use ($countrySlug) {
            if (!$countrySlug) {
                return;
            }
            $q->where(function ($qq) use ($countrySlug) {
                $qq->whereHas('countries', fn ($sq) => $sq->where('slug', $countrySlug))
                   ->orWhereHas('certifier.countries', fn ($sq) => $sq->where('slug', $countrySlug));
            });
        };

        $applyTextQuery = function ($q) use ($query) {
            if ($query === '') {
                return;
            }
            $q->where(function ($qq) use ($query) {
                $qq->where('name', 'LIKE', "%{$query}%")
                   ->orWhere('barcode', $query);
            });
        };

        $baseQuery = function () use ($applyCountry, $applyTextQuery) {
            $q = Product::active();
            $applyCountry($q);
            $applyTextQuery($q);
            return $q;
        };

        $baseQueryNoCountry = function () use ($applyTextQuery) {
            $q = Product::active();
            $applyTextQuery($q);
            return $q;
        };

        // 1. Search our local database first.
        $localQuery = $baseQuery();
        $localQuery->with(['brand', 'certifier', 'countries', 'category']);
        $this->applyCategoryFilter($localQuery, $categorySlug);
        $this->applyCertifierFilter($localQuery, $certifierSlug);
        $this->applyBrandFilter($localQuery, $brandSlug);
        $this->applyTipoFilter($localQuery, $tipo);

        $noOtherFilters = !$countrySlug && !$categorySlug && !$certifierSlug && !$brandSlug && !$tipo;

        if ($query !== '' && $noOtherFilters) {
            // Búsqueda de texto libre sin otros filtros: combinar con resultados externos, sin paginar.
            $localProducts = $localQuery->get();
            $externalProducts = $externalService->search($query);

            $products = $localProducts->concat($externalProducts)->unique(function ($item) {
                return $item->barcode ?? $item->unique_hash;
            });
            $isPaginated = false;
            $total = $products->count();
        } else {
            $products = $localQuery->orderBy('name')->paginate(self::PER_PAGE)->withQueryString();
            $isPaginated = true;
            $total = $products->total();
        }

        $matchingArticles = $query !== '' ? $this->searchArticles($query) : collect();

        // Facetas con conteo para la sidebar de filtros
        $categoryFacets = $this->buildCategoryFacet($baseQuery, $brandSlug, $tipo);
        $brandFacets = $this->buildBrandFacet($baseQuery, $categorySlug, $tipo);
        $tipoFacets = $this->buildTipoFacet($baseQuery, $categorySlug, $brandSlug);
        $countryFacets = $this->buildCountryFacet($baseQueryNoCountry, $categorySlug, $brandSlug, $tipo);

        $selectedCategoryModel = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;
        $selectedBrandModel = $brandSlug ? Brand::where('slug', $brandSlug)->first() : null;

        return view('welcome', [
            'products' => $products,
            'isPaginated' => $isPaginated,
            'total' => $total,
            'query' => $query,
            'selectedCountry' => $countrySlug,
            'selectedCategory' => $categorySlug,
            'selectedCertifier' => $certifierSlug,
            'selectedBrand' => $brandSlug,
            'selectedTipo' => $tipo,
            'selectedCategoryModel' => $selectedCategoryModel,
            'selectedBrandModel' => $selectedBrandModel,
            'categoryFacets' => $categoryFacets,
            'brandFacets' => $brandFacets,
            'tipoFacets' => $tipoFacets,
            'countryFacets' => $countryFacets,
            'tipoLabels' => self::TIPO_LABELS,
            'matchingArticles' => $matchingArticles,
        ]);
    }

    private function searchArticles(string $query): Collection
    {
        return Article::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->orderBy('sort_order')
            ->limit(6)
            ->get();
    }

    private function applyCategoryFilter($q, ?string $categorySlug): void
    {
        if (!$categorySlug) {
            return;
        }

        $category = Category::where('slug', $categorySlug)->with('children')->first();
        if (!$category) {
            return;
        }

        $q->whereIn('category_id', $category->selfAndDescendantIds());
    }

    private function applyCertifierFilter($q, ?string $certifierSlug): void
    {
        if (!$certifierSlug) {
            return;
        }
        $q->whereHas('certifier', fn ($sq) => $sq->where('slug', $certifierSlug));
    }

    private function applyBrandFilter($q, ?string $brandSlug): void
    {
        if (!$brandSlug) {
            return;
        }
        $q->whereHas('brand', fn ($sq) => $sq->where('slug', $brandSlug));
    }

    private function applyTipoFilter($q, ?string $tipo): void
    {
        if (!$tipo || !isset(self::KOSHER_STATUS_GROUPS[$tipo])) {
            return;
        }
        $q->whereIn('kosher_status', self::KOSHER_STATUS_GROUPS[$tipo]);
    }

    private function buildCategoryFacet(callable $baseQuery, ?string $brandSlug, ?string $tipo): Collection
    {
        $q = $baseQuery();
        $this->applyBrandFilter($q, $brandSlug);
        $this->applyTipoFilter($q, $tipo);

        $counts = $q->whereNotNull('category_id')
            ->select('category_id')
            ->selectRaw('count(*) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(self::CATEGORY_FACET_LIMIT)
            ->pluck('total', 'category_id');

        $categories = Category::whereIn('id', $counts->keys())->get()->keyBy('id');

        return $counts->map(fn ($total, $id) => [
            'category' => $categories->get($id),
            'total' => $total,
        ])->filter(fn ($facet) => $facet['category'] !== null)->values();
    }

    private function buildBrandFacet(callable $baseQuery, ?string $categorySlug, ?string $tipo): Collection
    {
        $q = $baseQuery();
        $this->applyCategoryFilter($q, $categorySlug);
        $this->applyTipoFilter($q, $tipo);

        $counts = $q->whereNotNull('brand_id')
            ->select('brand_id')
            ->selectRaw('count(*) as total')
            ->groupBy('brand_id')
            ->orderByDesc('total')
            ->limit(self::BRAND_FACET_LIMIT)
            ->pluck('total', 'brand_id');

        $brands = Brand::whereIn('id', $counts->keys())->get()->keyBy('id');

        return $counts->map(fn ($total, $id) => [
            'brand' => $brands->get($id),
            'total' => $total,
        ])->filter(fn ($facet) => $facet['brand'] !== null)->values();
    }

    private function buildTipoFacet(callable $baseQuery, ?string $categorySlug, ?string $brandSlug): Collection
    {
        $q = $baseQuery();
        $this->applyCategoryFilter($q, $categorySlug);
        $this->applyBrandFilter($q, $brandSlug);

        $rawCounts = $q->select('kosher_status')
            ->selectRaw('count(*) as total')
            ->groupBy('kosher_status')
            ->pluck('total', 'kosher_status');

        $result = collect();
        foreach (self::KOSHER_STATUS_GROUPS as $groupKey => $statuses) {
            $sum = 0;
            foreach ($statuses as $status) {
                $sum += $rawCounts->get($status, 0);
            }
            if ($sum > 0) {
                $result->push([
                    'tipo' => $groupKey,
                    'label' => self::TIPO_LABELS[$groupKey],
                    'total' => $sum,
                ]);
            }
        }

        return $result->sortByDesc('total')->values();
    }

    private function buildCountryFacet(callable $baseQueryNoCountry, ?string $categorySlug, ?string $brandSlug, ?string $tipo): Collection
    {
        $q = $baseQueryNoCountry();
        $this->applyCategoryFilter($q, $categorySlug);
        $this->applyBrandFilter($q, $brandSlug);
        $this->applyTipoFilter($q, $tipo);

        $result = collect();
        foreach (Country::all() as $country) {
            $total = (clone $q)->where(function ($qq) use ($country) {
                $qq->whereHas('countries', fn ($sq) => $sq->where('countries.id', $country->id))
                   ->orWhereHas('certifier.countries', fn ($sq) => $sq->where('countries.id', $country->id));
            })->count();

            if ($total > 0) {
                $result->push(['country' => $country, 'total' => $total]);
            }
        }

        return $result->sortByDesc('total')->values();
    }
}
