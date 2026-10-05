<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Certifier;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $articles = Article::published()->orderBy('sort_order')->limit(6)->get();

        // Cantidad de productos redondeada hacia abajo a la centena ("más de 5.900"),
        // cacheada para no contar en cada visita.
        $productCount = Cache::remember('home.product_count', 3600, function () {
            try {
                $n = Product::active()->count();
            } catch (\Throwable $e) {
                return 0;
            }

            return (int) (floor($n / 100) * 100);
        });

        $certifiers = Certifier::approved()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit(8)
            ->get();

        return view('home', compact('articles', 'productCount', 'certifiers'));
    }
}
