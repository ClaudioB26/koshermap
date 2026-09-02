<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductSubmissionController extends Controller
{
    public const KOSHER_STATUSES = ['Pareve', 'Dairy', 'Meat', 'Fish'];

    private function certifierOrFail(Request $request)
    {
        $user = $request->user();
        // isAdmin() tambien pasa: un admin no deberia tener MENOS acceso que un
        // certificador comun sobre su propia certificadora (ej: la cuenta del
        // dueño de la plataforma es a la vez admin y dueña de una certificadora
        // de prueba, y perderia "Mis productos" si solo se chequeara isCertifier()).
        abort_unless(($user->isCertifier() || $user->isAdmin()) && $user->certifier_id, 403, 'Esta sección es solo para certificadoras aprobadas.');

        return $user->certifier;
    }

    public function index(Request $request)
    {
        $certifier = $this->certifierOrFail($request);

        $products = $certifier->products()->with(['brand', 'category'])->latest()->paginate(20);

        return view('account.products.index', compact('products', 'certifier'));
    }

    public function create(Request $request)
    {
        $this->certifierOrFail($request);

        $brands     = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('account.products.create', [
            'brands'     => $brands,
            'categories' => $categories,
            'statuses'   => self::KOSHER_STATUSES,
            'product'    => new Product(),
        ]);
    }

    public function store(Request $request)
    {
        $certifier = $this->certifierOrFail($request);

        $validated = $this->validated($request);

        $product = new Product($validated);
        $product->certifier_id = $certifier->id;
        $product->brand_id     = $this->resolveBrandId($validated['brand_name'] ?? null);
        $product->source       = 'certifier';
        $product->is_active    = true;
        $product->slug         = $this->uniqueSlug($validated['name']);
        $product->save();

        return redirect()->route('account.products')
            ->with('success', "\"{$product->name}\" fue publicado.");
    }

    public function edit(Request $request, Product $product)
    {
        $certifier = $this->certifierOrFail($request);
        abort_unless($product->certifier_id === $certifier->id, 403);

        $brands     = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('account.products.edit', [
            'product'    => $product,
            'brands'     => $brands,
            'categories' => $categories,
            'statuses'   => self::KOSHER_STATUSES,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $certifier = $this->certifierOrFail($request);
        abort_unless($product->certifier_id === $certifier->id, 403);

        $validated = $this->validated($request, $product->id);

        $product->fill($validated);
        $product->brand_id = $this->resolveBrandId($validated['brand_name'] ?? null);
        $product->save();

        return redirect()->route('account.products')
            ->with('success', "\"{$product->name}\" fue actualizado.");
    }

    public function toggleActive(Request $request, Product $product)
    {
        $certifier = $this->certifierOrFail($request);
        abort_unless($product->certifier_id === $certifier->id, 403);

        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', $product->is_active
            ? "\"{$product->name}\" está visible en el catálogo."
            : "\"{$product->name}\" fue ocultado del catálogo.");
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'barcode'       => 'nullable|string|max:50',
            'kosher_status' => 'required|in:' . implode(',', self::KOSHER_STATUSES),
            'category_id'   => 'nullable|exists:categories,id',
            'brand_name'    => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:2000',
            'image_url'     => 'nullable|url|max:500',
        ]);
    }

    private function resolveBrandId(?string $brandName): ?int
    {
        if (!$brandName) {
            return null;
        }

        $brand = Brand::firstOrCreate(
            ['slug' => Str::slug($brandName)],
            ['name' => $brandName]
        );

        return $brand->id;
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $n = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$n}";
            $n++;
        }

        return $slug;
    }
}
