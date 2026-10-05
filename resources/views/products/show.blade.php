@extends('layouts.app')

@php
    $hasRealDescription = mb_strlen(trim((string) $product->description)) >= 200;
    $brandName = $product->brand->name ?? null;
    $pageTitle = $product->name . ($brandName && !str_contains(mb_strtolower($product->name), mb_strtolower($brandName)) ? ' - ' . $brandName : '') . ' - KosherMap';
    $metaDesc = $product->description
        ? \Illuminate\Support\Str::limit(trim($product->description), 155)
        : $product->name . ($brandName ? ' (' . $brandName . ')' : '') . ': ' . strtoupper($product->kosher_status) . ($product->certifier ? ' - ' . $product->certifier->name : '') . '.';
@endphp

@section('title', $pageTitle)
@section('meta_description', $metaDesc)
{{-- Solo se indexan las fichas con una descripción real; las demás son datos
     de catálogo sin texto propio y quedan noindex (se pueden seguir visitando). --}}
@section('robots', $hasRealDescription ? 'index, follow' : 'noindex, follow')
@if($product->image_url)
    @section('og_image', $product->image_url)
@endif

@push('head')
    @php
        $schema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description ?: null,
            'image' => $product->image_url ?: null,
            'brand' => $brandName ? ['@type' => 'Brand', 'name' => $brandName] : null,
            'category' => $product->category->name ?? null,
            'gtin' => preg_match('/^\d{8}$|^\d{12,14}$/', (string) $product->barcode) ? $product->barcode : null,
            'additionalProperty' => [
                ['@type' => 'PropertyValue', 'name' => 'Kosher', 'value' => $product->kosher_status],
                $product->certifier ? ['@type' => 'PropertyValue', 'name' => 'Kosher certifier', 'value' => $product->certifier->name] : null,
            ],
        ]);
        $schema['additionalProperty'] = array_values(array_filter($schema['additionalProperty']));
    @endphp
    {!! \App\Services\SchemaService::render($schema) !!}
    {!! \App\Services\SchemaService::render(\App\Services\SchemaService::breadcrumbSchema([
        ['name' => 'KosherMap', 'url' => url('/')],
        ['name' => __('product.products'), 'url' => route('search.index')],
        ['name' => $product->name, 'url' => route('products.show', $product->slug)],
    ])) !!}
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <a href="{{ route('search.index') }}" class="hover:text-blue-600">{{ __('product.products') }}</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="flex-shrink-0 flex items-center justify-center w-full md:w-48">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="max-h-48 w-auto object-contain rounded-xl" loading="eager">
                        @else
                            <div class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center text-4xl" aria-hidden="true">📦</div>
                        @endif
                    </div>

                    <div class="flex-grow">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                        <p class="text-blue-600 font-semibold text-lg mb-4">{{ $brandName ?? __('Generic Brand') }}</p>

                        <div class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold text-sm mb-6">
                            ✓ {{ __('Kosher Status') }}: {{ strtoupper($product->kosher_status) }}
                        </div>

                        <h2 class="sr-only">{{ __('product.product_details') }}</h2>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="flex justify-between gap-3 border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">{{ __('certifier') }}</dt>
                                <dd class="font-semibold text-gray-800 text-right">
                                    @if($product->certifier)
                                        <a href="{{ route('certifiers.index') }}" class="text-blue-600 hover:underline">{{ $product->certifier->name }}</a>
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between gap-3 border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">{{ __('product.brand') }}</dt>
                                <dd class="font-semibold text-gray-800 text-right">{{ $brandName ?? __('N/A') }}</dd>
                            </div>
                            <div class="flex justify-between gap-3 border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">{{ __('barcode') }}</dt>
                                <dd class="font-mono font-semibold text-gray-800">{{ $product->barcode ?: __('N/A') }}</dd>
                            </div>
                            @if($product->category)
                            <div class="flex justify-between gap-3 border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">{{ __('categories') }}</dt>
                                <dd class="font-semibold text-gray-800 text-right">{{ $product->category->name }}</dd>
                            </div>
                            @endif
                            @if(($product->source ?? 'local') !== 'local')
                            <div class="flex justify-between gap-3 border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">{{ __('product.source') }}</dt>
                                <dd class="text-gray-800 text-right">{{ $product->source }}</dd>
                            </div>
                            @endif
                        </dl>

                        @if($product->description)
                        <p class="mt-5 text-gray-600 text-sm leading-relaxed">{{ $product->description }}</p>
                        @endif

                        <p class="mt-5 text-xs text-gray-400">{{ __('product.ask_certifier') }}</p>
                    </div>
                </div>
            </div>

            @if($related->isNotEmpty())
            <div class="mt-6">
                <h2 class="text-lg font-bold text-gray-800 mb-3">
                    {{ $brandName ? __('product.more_from_brand', ['name' => $brandName]) : __('categories') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($related as $r)
                    <a href="{{ route('products.show', $r->slug) }}"
                       class="bg-white border border-gray-100 rounded-xl p-3 flex items-center gap-3 hover:shadow-md transition">
                        @if($r->image_url)
                            <img src="{{ $r->image_url }}" alt="" width="48" height="48" loading="lazy"
                                 class="h-12 w-12 object-contain rounded-md bg-white border border-gray-100 shrink-0">
                        @endif
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $r->name }}</p>
                            <p class="text-xs text-gray-500">{{ strtoupper($r->kosher_status) }}@if($r->certifier) · {{ $r->certifier->name }}@endif</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="lg:hidden mt-6">
                @include('partials.related_articles_sidebar')
            </div>
        </div>

        @if($relatedArticles->isNotEmpty())
        <aside class="hidden lg:block lg:w-[22rem] shrink-0">
            <div class="sticky top-20">
                @include('partials.related_articles_sidebar')
            </div>
        </aside>
        @endif
    </div>
</div>
@endsection
