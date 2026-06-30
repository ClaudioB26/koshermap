<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
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

    public function show(string $slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
