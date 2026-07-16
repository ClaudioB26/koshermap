<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class RelatedArticlesService
{
    public const COUNT = 4;

    // Mapea categorías de producto (slug) a artículos relevantes, del más al menos específico.
    private const CATEGORY_ARTICLES = [
        'lacteos-y-derivados' => ['carne-y-leche', 'que-significa-pareve'],
        'leche' => ['carne-y-leche'],
        'quesos' => ['queso-kosher-cuajo'],
        'yogurt' => ['carne-y-leche'],
        'crema-lactea' => ['carne-y-leche'],
        'manteca-y-margarina' => ['carne-y-leche', 'que-significa-pareve'],
        'quesos-no-lacteos' => ['kashrut-y-veganismo', 'queso-kosher-cuajo'],
        'leches-vegetales' => ['kashrut-y-veganismo', 'que-significa-pareve'],
        'crema-no-lactea' => ['kashrut-y-veganismo', 'que-significa-pareve'],

        'bebidas-de-soja' => ['kashrut-y-veganismo'],
        'vinos' => ['vino-kosher', 'vino-mevushal'],
        'cerveza' => ['alcohol-bebidas-espirituosas'],
        'whisky' => ['alcohol-bebidas-espirituosas'],
        'vodka' => ['alcohol-bebidas-espirituosas'],
        'sidras' => ['alcohol-bebidas-espirituosas'],
        'licores' => ['alcohol-bebidas-espirituosas', 'vino-kosher'],
        'tequila' => ['alcohol-bebidas-espirituosas'],
        'ron' => ['alcohol-bebidas-espirituosas'],
        'bebidas-alcoholicas' => ['alcohol-bebidas-espirituosas', 'vino-kosher'],

        'pan' => ['separar-la-jala', 'jametz-pesaj'],
        'panaderia-y-cereales' => ['jametz-pesaj', 'separar-la-jala'],
        'cereales' => ['jametz-pesaj'],
        'fideos-y-pastas' => ['jametz-pesaj'],
        'avena' => ['jametz-pesaj'],
        'arroz' => ['jametz-pesaj', 'vajilla-para-pesaj'],
        'galletas-y-crackers' => ['jametz-pesaj', 'como-leer-etiqueta-kosher'],
        'pan-rallado-y-rebozadores' => ['jametz-pesaj'],
        'harinas-y-premezclas' => ['jametz-pesaj'],

        'carnes-y-proteinas' => ['shejita-sacrificio-kosher', 'carne-y-leche'],
        'carnes-rojas' => ['shejita-sacrificio-kosher', 'glatt-kosher'],
        'carnes-blancas' => ['shejita-sacrificio-kosher'],
        'pescados-y-mariscos' => ['pescado-kosher-aletas-escamas'],
        'hamburguesas' => ['shejita-sacrificio-kosher', 'carne-y-leche'],
        'milanesas' => ['shejita-sacrificio-kosher', 'carne-y-leche'],
        'proteinas-vegetales' => ['kashrut-y-veganismo'],
        'huevos' => ['huevos-kosher'],
        'embutidos' => ['shejita-sacrificio-kosher', 'glatt-kosher'],

        'frutas-y-verduras' => ['insectos-frutas-verduras'],
        'frutas-frescas' => ['insectos-frutas-verduras'],
        'verduras-frescas' => ['insectos-frutas-verduras'],
        'frutas-enlatadas' => ['como-leer-etiqueta-kosher'],
        'verduras-enlatadas' => ['como-leer-etiqueta-kosher'],
        'ensaladas-preparadas' => ['insectos-frutas-verduras'],
        'legumbres' => ['insectos-frutas-verduras'],
        'frutos-secos' => ['frutos-secos-contaminacion-cruzada'],
        'conservas' => ['como-leer-etiqueta-kosher'],

        'dulces-y-postres' => ['gelatina-kosher', 'como-leer-etiqueta-kosher'],
        'chocolates' => ['carne-y-leche', 'que-significa-pareve'],
        'caramelos-y-chicles' => ['gelatina-kosher'],
        'mermeladas-y-dulces' => ['como-leer-etiqueta-kosher'],
        'helados' => ['carne-y-leche', 'gelatina-kosher'],
        'postres-en-polvo' => ['gelatina-kosher'],
        'galletas-dulces' => ['gelatina-kosher', 'como-leer-etiqueta-kosher'],
        'alfajores' => ['gelatina-kosher', 'carne-y-leche'],
        'tortas-y-budines' => ['carne-y-leche', 'como-leer-etiqueta-kosher'],

        'snacks-y-copetin' => ['errores-comunes-empezar-comer-kosher', 'como-leer-etiqueta-kosher'],
        'papas-fritas' => ['como-leer-etiqueta-kosher'],
        'snacks-de-maiz' => ['como-leer-etiqueta-kosher'],
    ];

    // Fallback por tipo kosher cuando el producto no tiene categoría o esta no está mapeada.
    private const KOSHER_STATUS_ARTICLES = [
        'Pareve' => ['que-significa-pareve'],
        'Dairy' => ['carne-y-leche'],
        'OU Dairy' => ['carne-y-leche'],
        'Meat' => ['shejita-sacrificio-kosher', 'carne-y-leche'],
        'OU Fish' => ['pescado-kosher-aletas-escamas'],
    ];

    // Relleno genérico para completar hasta COUNT cuando lo específico no alcanza.
    private const FILLER_ARTICLES = [
        'como-leer-etiqueta-kosher',
        'certificaciones-kosher-mundo',
        'que-significa-pareve',
        'errores-comunes-empezar-comer-kosher',
    ];

    private const CERTIFIER_ARTICLES = [
        'simbolos-certificacion-kosher',
        'certificaciones-kosher-mundo',
        'como-leer-etiqueta-kosher',
        'bishul-akum',
    ];

    private const COUNTRY_ARTICLES = [
        'certificaciones-kosher-mundo',
        'calendario-judio-festividades-alimentacion',
        'comer-kosher-restaurante',
        'armar-cocina-kosher',
    ];

    private const PLACES_ARTICLES = [
        'comer-kosher-restaurante',
        'certificaciones-kosher-mundo',
        'calendario-judio-festividades-alimentacion',
        'errores-comunes-empezar-comer-kosher',
    ];

    private const BRAND_FALLBACK_ARTICLES = [
        'como-leer-etiqueta-kosher',
        'simbolos-certificacion-kosher',
        'certificaciones-kosher-mundo',
        'errores-comunes-empezar-comer-kosher',
    ];

    public function forProduct(Product $product): Collection
    {
        $slugs = [];

        $category = $product->category;
        while ($category && count($slugs) < self::COUNT) {
            $this->appendUnique($slugs, self::CATEGORY_ARTICLES[$category->slug] ?? []);
            $category = $category->parent;
        }

        if (count($slugs) < self::COUNT) {
            $this->appendUnique($slugs, self::KOSHER_STATUS_ARTICLES[$product->kosher_status] ?? []);
        }

        $this->appendUnique($slugs, self::FILLER_ARTICLES);

        return $this->resolve($slugs);
    }

    public function forBrand(Brand $brand): Collection
    {
        $topCategoryId = $brand->products()->active()
            ->whereNotNull('category_id')
            ->select('category_id')
            ->groupBy('category_id')
            ->orderByRaw('COUNT(*) DESC')
            ->value('category_id');

        $slugs = [];

        $category = $topCategoryId ? Category::find($topCategoryId) : null;
        while ($category && count($slugs) < self::COUNT) {
            $this->appendUnique($slugs, self::CATEGORY_ARTICLES[$category->slug] ?? []);
            $category = $category->parent;
        }

        $this->appendUnique($slugs, self::BRAND_FALLBACK_ARTICLES);

        return $this->resolve($slugs);
    }

    public function forCertifier(): Collection
    {
        return $this->resolve(self::CERTIFIER_ARTICLES);
    }

    public function forCountry(): Collection
    {
        return $this->resolve(self::COUNTRY_ARTICLES);
    }

    public function forPlaces(): Collection
    {
        return $this->resolve(self::PLACES_ARTICLES);
    }

    private function appendUnique(array &$slugs, array $candidates): void
    {
        foreach ($candidates as $slug) {
            if (count($slugs) >= self::COUNT) break;
            if (!in_array($slug, $slugs, true)) {
                $slugs[] = $slug;
            }
        }
    }

    private function resolve(array $slugs): Collection
    {
        $slugs = array_slice($slugs, 0, self::COUNT);

        $articles = Article::published()->whereIn('slug', $slugs)->get()->keyBy('slug');

        return collect($slugs)->map(fn ($s) => $articles->get($s))->filter()->values();
    }
}
