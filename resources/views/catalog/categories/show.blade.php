@extends('layouts.app')

@section('title', __('Category') . ': ' . $category->name . ' - KosherStatus')

@section('content')
    <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('categories.index') }}" class="hover:text-blue-600">{{ __('Categories') }}</a>
            </li>
            @if($category->parent)
            <li>
                <div class="flex items-center">
                    <span class="mx-2">/</span>
                    <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-blue-600">{{ $category->parent->name }}</a>
                </div>
            </li>
            @endif
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="mx-2">/</span>
                    <span class="font-medium text-gray-800">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-800">{{ $category->name }}</h1>
    </div>
    
    @if($category->children->count() > 0)
    <div class="mb-10">
        <h3 class="text-lg font-semibold mb-3 text-gray-700">{{ __('Subcategories') }}</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($category->children as $child)
            <a href="{{ route('categories.show', $child->slug) }}" class="bg-white px-4 py-2 rounded-full shadow-sm border border-gray-200 hover:border-blue-400 hover:text-blue-600 transition">
                {{ $child->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition border border-gray-100">
            @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-contain mb-4">
            @endif
            <h3 class="font-bold text-lg mb-2 text-gray-800">{{ $product->name }}</h3>
            <p class="text-gray-600 text-sm mb-2">{{ $product->brand->name ?? __('Unknown Brand') }}</p>
            <div class="flex justify-between items-center mt-4">
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    {{ $product->kosher_status }}
                </span>
                <a href="{{ route('products.show', $product->slug) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">{{ __('View details') }}</a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endsection
