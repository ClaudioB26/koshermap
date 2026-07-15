<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
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

    public function show($slug)
    {
        // Buscamos el producto por su slug (ej: /product/oreo)
        // Carga también la marca y el certificador para que no haya errores
        // Los productos despublicados (is_active=false) no deben ser accesibles ni por URL directa
        $product = Product::active()->with(['brand', 'certifier', 'category'])->where('slug', $slug)->firstOrFail();

        $relatedArticles = $this->relatedArticles($product);

        return view('products.show', compact('product', 'relatedArticles'));
    }

    private function relatedArticles(Product $product): Collection
    {
        $slugs = [];

        $category = $product->category;
        while ($category && count($slugs) < 3) {
            foreach (self::CATEGORY_ARTICLES[$category->slug] ?? [] as $articleSlug) {
                if (!in_array($articleSlug, $slugs, true)) {
                    $slugs[] = $articleSlug;
                }
            }
            $category = $category->parent;
        }

        if (empty($slugs)) {
            $slugs = self::KOSHER_STATUS_ARTICLES[$product->kosher_status] ?? [];
        }

        if (empty($slugs)) {
            $slugs = ['como-leer-etiqueta-kosher'];
        }

        $slugs = array_slice($slugs, 0, 3);

        $articles = Article::published()->whereIn('slug', $slugs)->get()->keyBy('slug');

        return collect($slugs)->map(fn ($s) => $articles->get($s))->filter()->values();
    }
}


