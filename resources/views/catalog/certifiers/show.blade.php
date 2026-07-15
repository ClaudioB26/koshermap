@extends('layouts.app')

@section('title', __('Certifier') . ': ' . $certifier->name . ' - KosherMap')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-800">{{ __('Certifier') }}: {{ $certifier->name }}</h1>
        <a href="{{ route('certifiers.index') }}" class="text-blue-600 hover:underline">{{ __('View all certifiers') }}</a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 min-w-0">
            @if($categories->count() > 0)
            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                    <span>🏷️ {{ __('Filter by Category in') }} {{ $certifier->name }}</span>
                    @if(request('category'))
                        <a href="{{ route('certifiers.show', $certifier->slug) }}" class="ml-auto text-sm text-red-500 hover:text-red-700">✕ {{ __('Clear filter') }}</a>
                    @endif
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $cat)
                    <a href="{{ route('certifiers.show', ['slug' => $certifier->slug, 'category' => $cat->slug]) }}"
                       class="px-3 py-1 rounded-full text-sm border transition
                       {{ request('category') == $cat->slug
                            ? 'bg-blue-600 text-white border-blue-600 shadow-md'
                            : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400 hover:text-blue-600'
                       }}">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
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
