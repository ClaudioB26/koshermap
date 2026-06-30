@extends('layouts.app')

@section('title', 'KosherMap - ' . __('Search products'))

@section('content')

{{-- Hero: cuando no hay búsqueda activa --}}
@if(!isset($query) && !request('country') && !request('category') && !request('certifier') && !request('brand') && !request('tipo'))
<div class="text-center py-16">
    <h1 class="text-5xl font-black text-blue-600 mb-3">Kosher<span class="text-gray-800">Map</span></h1>
    <p class="text-gray-400 text-lg mb-12">{{ trans('home.subtitle') }}</p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
        <button onclick="document.getElementById('query-input').closest('form').requestSubmit()"
                class="flex-1 flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-700
                       text-white font-bold py-5 px-6 rounded-2xl shadow-lg transition text-base">
            🛒 <span>Buscar producto</span>
        </button>
        <a href="{{ route('places.index') }}"
           class="flex-1 flex items-center justify-center gap-3 bg-white hover:bg-gray-50
                  text-gray-800 font-bold py-5 px-6 rounded-2xl shadow-lg border border-gray-200 transition text-base">
            📍 <span>Encontrar local</span>
        </a>
    </div>

    {{-- Sección introductoria para AdSense / SEO --}}
    <div class="mt-16 text-left max-w-3xl mx-auto">
        <p class="text-sm font-semibold text-blue-600 uppercase tracking-widest mb-1">
            {{ trans('home.tagline') }}
        </p>
        <p class="text-gray-600 leading-relaxed mb-8">
            {!! trans('home.description') !!}
        </p>

        {{-- Stats --}}
        <div class="flex flex-wrap justify-center gap-8 mb-10 text-center">
            <div>
                <p class="text-2xl font-black text-blue-600">{{ trans('home.stat_products') }}</p>
            </div>
            <div>
                <p class="text-2xl font-black text-blue-600">{{ trans('home.stat_places') }}</p>
            </div>
            <div>
                <p class="text-2xl font-black text-blue-600">{{ trans('home.stat_langs') }}</p>
            </div>
        </div>

        {{-- Feature cards --}}
        <h2 class="text-lg font-bold text-gray-800 mb-4">{{ trans('home.features_title') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                <p class="font-semibold text-gray-800 mb-1">{{ trans('home.feature_1_title') }}</p>
                <p class="text-sm text-gray-500">{{ trans('home.feature_1_body') }}</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                <p class="font-semibold text-gray-800 mb-1">{{ trans('home.feature_2_title') }}</p>
                <p class="text-sm text-gray-500">{{ trans('home.feature_2_body') }}</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm">
                <p class="font-semibold text-gray-800 mb-1">{{ trans('home.feature_3_title') }}</p>
                <p class="text-sm text-gray-500">{{ trans('home.feature_3_body') }}</p>
            </div>
        </div>

        {{-- Links a páginas informativas --}}
        <div class="flex flex-wrap gap-3 text-sm">
            <a href="{{ route('pages.que-es-kosher') }}"
               class="px-4 py-2 bg-blue-50 text-blue-700 rounded-full hover:bg-blue-100 transition font-medium">
                {{ trans('home.learn_kosher') }}
            </a>
            <a href="{{ route('articles.index') }}"
               class="px-4 py-2 bg-blue-50 text-blue-700 rounded-full hover:bg-blue-100 transition font-medium">
                📰 Artículos
            </a>
            <a href="{{ route('pages.sobre-nosotros') }}"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition font-medium">
                {{ trans('home.about_us') }}
            </a>
        </div>
    </div>
</div>

@else

<div class="flex flex-col lg:flex-row gap-6">

    {{-- Filtros: versión mobile colapsable --}}
    <div class="lg:hidden" x-data="{ filtersOpen: false }">
        <button @click="filtersOpen = !filtersOpen" type="button"
                class="w-full mb-3 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 flex items-center justify-between shadow-sm">
            <span>🔧 Filtros</span>
            <span x-text="filtersOpen ? '▲' : '▼'"></span>
        </button>
        <div x-show="filtersOpen" x-transition class="mb-4">
            @include('partials.search-filters-sidebar')
        </div>
    </div>

    {{-- Filtros: sidebar desktop --}}
    <aside class="hidden lg:block w-64 shrink-0">
        @include('partials.search-filters-sidebar')
    </aside>

    <div class="flex-1 min-w-0">

        {{-- Contador de resultados --}}
        <p class="text-sm text-gray-500 mb-4">
            {{ number_format($total) }} resultado{{ $total !== 1 ? 's' : '' }}
            @if($query) {{ __('for') }} "<strong>{{ $query }}</strong>" @endif
        </p>

        {{-- Filtros activos --}}
        @if(request('country') || request('category') || request('certifier') || request('brand') || request('tipo'))
        <div class="flex gap-2 mb-6 flex-wrap">
            @if(request('country'))
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold flex items-center gap-2">
                📍 {{ ucwords(str_replace('-', ' ', request('country'))) }}
                <a href="{{ route('home', request()->except(['country', 'page'])) }}" class="text-blue-500 hover:text-blue-700">✕</a>
            </span>
            @endif
            @if(request('category'))
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold flex items-center gap-2">
                🏷️ {{ $selectedCategoryModel->name ?? ucwords(str_replace('-', ' ', request('category'))) }}
                <a href="{{ route('home', request()->except(['category', 'page'])) }}" class="text-green-500 hover:text-green-700">✕</a>
            </span>
            @endif
            @if(request('certifier'))
            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold flex items-center gap-2">
                🏅 {{ ucwords(str_replace('-', ' ', request('certifier'))) }}
                <a href="{{ route('home', request()->except(['certifier', 'page'])) }}" class="text-purple-500 hover:text-purple-700">✕</a>
            </span>
            @endif
            @if(request('brand'))
            <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold flex items-center gap-2">
                🏭 {{ $selectedBrandModel->name ?? ucwords(str_replace('-', ' ', request('brand'))) }}
                <a href="{{ route('home', request()->except(['brand', 'page'])) }}" class="text-orange-500 hover:text-orange-700">✕</a>
            </span>
            @endif
            @if(request('tipo'))
            <span class="px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-sm font-semibold flex items-center gap-2">
                🥘 {{ $tipoLabels[request('tipo')] ?? request('tipo') }}
                <a href="{{ route('home', request()->except(['tipo', 'page'])) }}" class="text-pink-500 hover:text-pink-700">✕</a>
            </span>
            @endif
        </div>
        @endif

        {{-- Artículos relacionados a la búsqueda --}}
        @if(isset($matchingArticles) && $matchingArticles->isNotEmpty())
        <div class="mb-6">
            <p class="text-sm font-semibold text-gray-500 mb-2">📰 Artículos relacionados</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($matchingArticles as $article)
                <a href="{{ route('articles.show', $article->slug) }}"
                   class="block bg-blue-50 hover:bg-blue-100 transition rounded-xl p-4 border border-blue-100">
                    <p class="font-semibold text-blue-800 text-sm mb-1">{{ $article->title }}</p>
                    <p class="text-xs text-blue-600 line-clamp-2">{{ $article->excerpt }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Resultados --}}
        @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($products as $i => $product)
            @if($i > 0 && $i % 6 === 0)
            </div>
            @include('partials.ad_banner')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @endif
            <a href="/product/{{ $product->slug }}"
               class="bg-white p-4 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 border border-gray-100 flex items-center justify-between transition-all duration-200 group">
                <div class="flex items-center space-x-4">
                    @php
                        $productCertifier = $product->certifier;
                        $certSlug  = $productCertifier->slug ?? '';
                        $certName  = $productCertifier->name ?? __('Unknown');
                        if ($certSlug === 'ou') {
                            $badgeColor = 'bg-white border-2 border-black text-black font-serif';
                            $badgeText  = 'Ⓤ';
                        } elseif ($certSlug === 'kmd') {
                            $badgeColor = 'bg-yellow-100 text-yellow-800';
                            $badgeText  = 'KMD';
                        } elseif (str_contains(strtolower($certName), 'ka')) {
                            $badgeColor = 'bg-blue-50 text-blue-800';
                            $badgeText  = 'KA';
                        } else {
                            $badgeColor = 'bg-gray-100 text-gray-600';
                            $badgeText  = 'K';
                        }
                    @endphp
                    <div class="h-10 w-10 flex items-center justify-center rounded-full {{ $badgeColor }} font-bold text-sm shadow-sm shrink-0"
                         title="{{ $certName }}">
                        {{ $badgeText }}
                    </div>
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         class="h-12 w-12 object-contain rounded-md bg-white border border-gray-100 shrink-0">
                    @endif
                    <div>
                        <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $product->brand->name ?? __('Brand') }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end shrink-0 ml-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold mb-1">
                        {{ $product->kosher_status }}
                    </span>
                    @if(($product->source ?? 'local') !== 'local')
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider">{{ $product->source }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        @if($isPaginated)
        <div class="mt-8">
            {{ $products->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg mb-3">
                @if($query)
                    {{ __('No results found for') }} "<strong>{{ $query }}</strong>"
                @else
                    No se encontraron productos con estos filtros.
                @endif
            </p>
            <a href="{{ route('places.index', ['query' => $query]) }}"
               class="inline-flex items-center gap-2 text-blue-600 hover:underline text-sm">
                📍 ¿Buscás un local? Ver en Lugares →
            </a>
        </div>
        @endif
    </div>
</div>

@endif

@endsection
