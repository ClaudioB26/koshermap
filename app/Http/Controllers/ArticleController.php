<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ArticleController extends Controller
{
    public const CATEGORY_LABELS = [
        'halajot'         => 'Halajot',
        'kasherizacion'   => 'Kasherización',
        'festividades'    => 'Festividades',
        'productos'       => 'Productos',
        'kashrut-basico'  => 'Kashrut Básico',
        'vida-diaria'     => 'Vida Diaria',
    ];

    public function index(Request $request, string $locale = 'es')
    {
        // Las rutas con prefijo de idioma (/en/articles, /pt/artigos, etc.)
        // fuerzan el locale de la URL, sin importar lo que diga la sesión.
        App::setLocale($locale);

        $category = $request->input('category');

        $query = Article::published()->orderBy('sort_order');
        if ($category && isset(self::CATEGORY_LABELS[$category])) {
            $query->where('category', $category);
        } else {
            $category = null;
        }

        $articles = $query->get();
        $categories = self::CATEGORY_LABELS;
        $selectedCategory = $category;

        return view('articles.index', compact('articles', 'categories', 'selectedCategory'));
    }

    public function show(Request $request, string $slug, string $locale = 'es')
    {
        App::setLocale($locale);

        if ($locale === 'es') {
            $article = Article::published()->where('slug', $slug)->first();
        } else {
            // Son 30 artículos: filtrar en PHP es más simple y portable entre
            // motores de base de datos que un JSON_EXTRACT específico de MySQL.
            $article = Article::published()->get()
                ->first(fn ($a) => $a->slugFor($locale) === $slug);
        }

        abort_if(!$article, 404);

        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
