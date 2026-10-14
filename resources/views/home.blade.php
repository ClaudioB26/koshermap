@extends('layouts.app')

@php
    $locale = app()->getLocale();
    $h = fn ($k, $r = []) => __('homepage.' . $k, $r);
    $count = number_format($productCount, 0, ',', '.');
@endphp

@section('title', $h('meta_title'))
@section('meta_description', $h('meta_description'))
@section('canonical', url('/'))

@push('head')
    {!! \App\Services\SchemaService::render(\App\Services\SchemaService::organizationSchema()) !!}
    {!! \App\Services\SchemaService::render(\App\Services\SchemaService::webSiteSchema()) !!}
@endpush

@section('content')
<section class="bg-gradient-to-b from-blue-50 to-white">
    <div class="max-w-4xl mx-auto px-4 pt-14 pb-10 text-center">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight mb-4">{{ $h('h1') }}</h1>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">{{ $h('tagline') }}</p>

        <form action="{{ route('search.index') }}" method="GET" class="flex max-w-xl mx-auto gap-2" role="search">
            <input type="search" name="query" required
                   placeholder="{{ $h('search_placeholder') }}"
                   aria-label="{{ $h('search_placeholder') }}"
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-3">
                {{ __('search') }}
            </button>
        </form>
    </div>
</section>

<div class="max-w-5xl mx-auto px-4 pb-14">
    <p class="text-gray-600 leading-relaxed max-w-3xl mx-auto text-center mb-12">
        {{ $h('intro', ['count' => $count]) }}
    </p>

    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">{{ $h('blocks_title') }}</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-14">
        @foreach([
            // Íconos de trazo (estilo Heroicons, 24x24): sobrios y del color de la marca.
            ['M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25', 'b_articles', \App\Models\Article::indexUrlFor($locale)],
            ['m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z', 'b_products', route('search.index')],
            ['M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z', 'b_certifiers', route('certifiers.index')],
            ['M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'b_trust', route('pages.sobre-nosotros')],
        ] as [$iconPath, $key, $link])
            <a href="{{ $link }}" class="block bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition p-5">
                {{-- Estilos en línea a propósito: el CSS de producción se compila en local y no incluye clases nuevas. --}}
                <div class="mb-3" aria-hidden="true"
                     style="width:44px;height:44px;border-radius:10px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" width="24" height="24" style="display:block">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ $h($key . '_title') }}</h3>
                <p class="text-sm text-gray-500">{{ $h($key . '_body') }}</p>
            </a>
        @endforeach
    </div>

    @if($articles->isNotEmpty())
        <div class="flex items-baseline justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">{{ $h('articles_title') }}</h2>
            <a href="{{ \App\Models\Article::indexUrlFor($locale) }}" class="text-sm text-blue-600 hover:underline">{{ $h('articles_all') }} →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-14">
            @foreach($articles as $a)
                <a href="{{ $a->urlFor($locale) ?? route('articles.show', $a->slug) }}"
                   class="group flex flex-col bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    @if($a->thumbnail)
                        <img src="{{ $a->thumbnail_small }}" alt="{{ $a->title }}" loading="lazy" decoding="async" width="480" height="320"
                             class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center">
                            <span class="text-5xl opacity-70" aria-hidden="true">{{ $a->category_icon }}</span>
                        </div>
                    @endif
                    <div class="p-5 flex-1">
                        <h3 class="font-bold text-gray-800 mb-2 leading-snug group-hover:text-blue-700 transition">{{ $a->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-3">{{ $a->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    @if($certifiers->isNotEmpty())
        <div class="flex items-baseline justify-between mb-2">
            <h2 class="text-2xl font-bold text-gray-900">{{ $h('certifiers_title') }}</h2>
            <a href="{{ route('certifiers.index') }}" class="text-sm text-blue-600 hover:underline">{{ $h('certifiers_all') }} →</a>
        </div>
        <p class="text-gray-500 mb-6">{{ $h('certifiers_body') }}</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-14">
            @foreach($certifiers as $c)
                <a href="{{ route('certifiers.index') }}"
                   class="bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition p-4 text-center">
                    <p class="font-semibold text-gray-800">{{ $c->name }}</p>
                    @if($c->products_count)
                        <p class="text-xs text-gray-500 mt-1">{{ $h('products_count', ['count' => number_format($c->products_count, 0, ',', '.')]) }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    @endif

    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $h('learn_title') }}</h2>
    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-14 text-blue-700">
        <li><a class="hover:underline" href="{{ route('pages.que-es-kosher') }}">{{ $h('learn_kosher') }}</a></li>
        <li><a class="hover:underline" href="{{ route('pages.kashrut') }}">{{ $h('learn_kashrut') }}</a></li>
        <li><a class="hover:underline" href="{{ route('pages.judaismo') }}">{{ $h('learn_judaism') }}</a></li>
        <li><a class="hover:underline" href="{{ route('pages.etiqueta-kosher') }}">{{ $h('learn_label') }}</a></li>
    </ul>

    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 text-center">
        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('certifier_signup_title') }}</h2>
        <p class="text-gray-600 mb-4 max-w-xl mx-auto">{{ __('certifier_signup_body') }}</p>
        <a href="{{ route('certifiers.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-5 py-3">{{ __('certifier_signup_cta') }}</a>
    </div>
</div>
@endsection
