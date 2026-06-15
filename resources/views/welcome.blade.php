@extends('layouts.app')

@section('title', 'KosherMap - ' . __('Search products'))

@section('content')

{{-- Hero: cuando no hay búsqueda activa --}}
@if(!isset($query) && !request('country') && !request('category') && !request('certifier'))
<div class="text-center py-16">
    <h1 class="text-5xl font-black text-blue-600 mb-3">Kosher<span class="text-gray-800">Map</span></h1>
    <p class="text-gray-400 text-lg mb-12">Encontrá productos y locales con certificación kosher</p>

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
</div>

@else

{{-- Filtros activos --}}
@if(request('country') || request('category') || request('certifier'))
<div class="flex gap-2 mb-6 flex-wrap">
    @if(request('country'))
    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold flex items-center gap-2">
        📍 {{ ucwords(str_replace('-', ' ', request('country'))) }}
        <a href="{{ route('home', request()->except('country')) }}" class="text-blue-500 hover:text-blue-700">✕</a>
    </span>
    @endif
    @if(request('category'))
    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold flex items-center gap-2">
        🏷️ {{ ucwords(str_replace('-', ' ', request('category'))) }}
        <a href="{{ route('home', request()->except('category')) }}" class="text-green-500 hover:text-green-700">✕</a>
    </span>
    @endif
    @if(request('certifier'))
    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold flex items-center gap-2">
        🏅 {{ ucwords(str_replace('-', ' ', request('certifier'))) }}
        <a href="{{ route('home', request()->except('certifier')) }}" class="text-purple-500 hover:text-purple-700">✕</a>
    </span>
    @endif
</div>
@endif

{{-- Resultados --}}
@if(isset($products) && $products->count() > 0)
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
                $certifier = $product->certifier;
                $certSlug  = $certifier->slug ?? '';
                $certName  = $certifier->name ?? __('Unknown');
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
@elseif(isset($query))
<div class="text-center py-16">
    <p class="text-gray-500 text-lg mb-3">
        {{ __('No results found for') }} "<strong>{{ $query }}</strong>"
    </p>
    <a href="{{ route('places.index', ['query' => $query]) }}"
       class="inline-flex items-center gap-2 text-blue-600 hover:underline text-sm">
        📍 ¿Buscás un local? Ver en Lugares →
    </a>
</div>
@endif

@endif

@endsection
