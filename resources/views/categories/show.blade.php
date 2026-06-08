@extends('layouts.app')

@section('title', "{$category->name} - {$certifier->name}")

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
        <span>/</span>
        <a href="{{ route('certifiers.show', $certifier->slug) }}" class="hover:text-blue-600">{{ $certifier->name }}</a>
        <span>/</span>
        <a href="{{ route('certifiers.categories.tree', $certifier->slug) }}" class="hover:text-blue-600">Categorías</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">{{ $category->name }}</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="text-gray-600 mt-2">
                    Productos kosher de {{ $certifier->name }} en la categoría {{ $category->name }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Certificadora</div>
                <div class="text-lg font-semibold text-blue-600">{{ $certifier->name }}</div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow group">
                    <div class="aspect-square bg-gray-100 flex items-center justify-center p-4">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform">
                        @else
                            <div class="text-6xl text-gray-400 group-hover:text-gray-500 transition-colors">
                                📦
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $product->kosher_status }}
                            </span>
                            @if($product->brand)
                                <span class="inline-block ml-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                    {{ $product->brand->name }}
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('products.show', $product->slug) }}" 
                               class="hover:text-blue-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        @if($product->barcode)
                            <div class="text-xs text-gray-500">
                                <span class="font-mono">{{ $product->barcode }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay productos</h3>
            <p class="mt-1 text-sm text-gray-500">
                No se encontraron productos en la categoría {{ $category->name }} para {{ $certifier->name }}.
            </p>
            <div class="mt-6">
                <a href="{{ route('certifiers.categories.tree', $certifier->slug) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Ver otras categorías
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
