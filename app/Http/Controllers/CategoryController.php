<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certifier;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Mostrar el árbol de categorías para una certificadora específica
     */
    public function tree($certifierSlug)
    {
        $certifier = Certifier::where('slug', $certifierSlug)->firstOrFail();
        
        // Obtener categorías principales con sus subcategorías y conteo de productos
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function($query) use ($certifier) {
                $query->withCount(['products' => function($query) use ($certifier) {
                    $query->where('certifier_id', $certifier->id);
                }]);
            }])
            ->withCount(['products' => function($query) use ($certifier) {
                $query->where('certifier_id', $certifier->id);
            }])
            ->orderBy('name')
            ->get();

        return view('categories.tree', compact('categories', 'certifier'));
    }

    /**
     * Mostrar productos de una categoría específica para una certificadora
     */
    public function show($certifierSlug, $categorySlug)
    {
        $certifier = Certifier::where('slug', $certifierSlug)->firstOrFail();
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Obtener productos de esta categoría y subcategorías
        $categoryIds = $this->getCategoryWithChildren($category->id);
        
        $products = $category->products()
            ->active()
            ->where('certifier_id', $certifier->id)
            ->with('brand', 'certifier')
            ->orderBy('name')
            ->paginate(20);

        // Obtener breadcrumb
        $breadcrumb = $this->getBreadcrumb($category);

        return view('categories.show', compact('products', 'category', 'certifier', 'breadcrumb'));
    }

    /**
     * Obtener IDs de categoría y todas sus subcategorías
     */
    private function getCategoryWithChildren($categoryId)
    {
        $categoryIds = [$categoryId];
        
        $children = Category::where('parent_id', $categoryId)->get();
        foreach ($children as $child) {
            $categoryIds = array_merge($categoryIds, $this->getCategoryWithChildren($child->id));
        }
        
        return $categoryIds;
    }

    /**
     * Generar breadcrumb para una categoría
     */
    private function getBreadcrumb($category)
    {
        $breadcrumb = [];
        $current = $category;
        
        while ($current) {
            array_unshift($breadcrumb, [
                'name' => $current->name,
                'slug' => $current->slug
            ]);
            $current = $current->parent;
        }
        
        return $breadcrumb;
    }

    /**
     * API para obtener categorías en formato JSON
     */
    public function api($certifierSlug)
    {
        $certifier = Certifier::where('slug', $certifierSlug)->firstOrFail();
        
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function($query) use ($certifier) {
                $query->withCount(['products' => function($query) use ($certifier) {
                    $query->where('certifier_id', $certifier->id);
                }]);
            }])
            ->withCount(['products' => function($query) use ($certifier) {
                $query->where('certifier_id', $certifier->id);
            }])
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }
}
