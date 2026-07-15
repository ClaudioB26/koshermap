@extends('layouts.app')

@section('title', $brand->name . ' - Productos Kosher - KosherMap')
@section('meta_description', 'Productos kosher de ' . $brand->name . ' disponibles en KosherMap. Verificá la certificación, el tipo (parve, lácteo, cárnico) y encontrá dónde conseguirlos.')
@section('robots', 'noindex, follow')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-800">{{ $brand->name }}</h1>
        <a href="{{ route('brands.index') }}" class="text-blue-600 hover:underline text-sm">← Ver todas las marcas</a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 min-w-0">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product->slug) }}"
                   class="bg-white p-4 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 border border-gray-100 flex items-center justify-between transition-all duration-200 group">
                    <div class="flex items-center space-x-4">
                        @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                             class="h-12 w-12 object-contain rounded-md bg-white border border-gray-100 shrink-0">
                        @endif
                        <div>
                            <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $product->category->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end shrink-0 ml-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold mb-1">
                            {{ $product->kosher_status }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $product->certifier->name ?? '' }}</span>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <p class="text-center text-gray-500 py-12">No hay productos disponibles para esta marca.</p>
            @endif
        </div>

        @if($relatedArticles->isNotEmpty())
        <aside class="hidden lg:block lg:w-[26rem] shrink-0">
            <div class="sticky top-20">
                @include('partials.related_articles_sidebar')
            </div>
        </aside>
        @endif
    </div>

    <div class="lg:hidden mt-6">
        @include('partials.related_articles_sidebar')
    </div>
@endsection
