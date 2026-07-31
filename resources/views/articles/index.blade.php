@extends('layouts.app')

@php
    $locale = app()->getLocale();
    $indexUrl = \App\Models\Article::indexUrlFor($locale);
    $pageCopy = [
        'es' => ['title' => 'Artículos sobre Kashrut', 'intro' => 'Guías prácticas sobre halajot, kasherización, festividades y vida diaria kosher.', 'all' => 'Todos'],
        'en' => ['title' => 'Articles About Kashrut', 'intro' => 'Practical guides on Jewish law, koshering, festivals and everyday kosher life.', 'all' => 'All'],
        'pt' => ['title' => 'Artigos sobre Kashrut', 'intro' => 'Guias práticos sobre halachot, casherização, festividades e vida diária kosher.', 'all' => 'Todos'],
        'fr' => ['title' => 'Articles sur la Cacherout', 'intro' => 'Guides pratiques sur la halakha, la cachérisation, les fêtes et la vie quotidienne cachère.', 'all' => 'Tous'],
        'ru' => ['title' => 'Статьи о кашруте', 'intro' => 'Практические руководства по еврейскому закону, кошерованию, праздникам и повседневной кошерной жизни.', 'all' => 'Все'],
        'he' => ['title' => 'מאמרים על כשרות', 'intro' => 'מדריכים מעשיים על הלכות, הכשרה, חגים וחיי היום-יום הכשרים.', 'all' => 'הכול'],
    ][$locale] ?? null;
    $pageCopy = $pageCopy ?? ['title' => 'Artículos sobre Kashrut', 'intro' => 'Guías prácticas sobre halajot, kasherización, festividades y vida diaria kosher.', 'all' => 'Todos'];
@endphp

@section('title', $pageCopy['title'] . ' — KosherMap')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">{{ \App\Models\Article::sectionLabelFor($locale) }}</span>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $pageCopy['title'] }}</h1>
    <p class="text-gray-500 mb-6">{{ $pageCopy['intro'] }}</p>

    {{-- Filtro por categoría --}}
    <div class="flex flex-wrap gap-2 mb-10">
        <a href="{{ $indexUrl }}"
           class="px-4 py-2 rounded-full text-sm font-medium transition {{ !$selectedCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            {{ $pageCopy['all'] }}
        </a>
        @foreach($categories as $slug => $label)
            <a href="{{ $indexUrl }}?category={{ $slug }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition {{ $selectedCategory === $slug ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($articles as $article)
            <a href="{{ $article->urlFor($locale) ?? route('articles.show', $article->slug) }}"
               class="group flex flex-col bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                @if($article->thumbnail)
                    <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" loading="lazy"
                         class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center">
                        <span class="text-5xl opacity-70" aria-hidden="true">{{ $article->category_icon }}</span>
                    </div>
                @endif
                <div class="p-5 flex-1">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">{{ $categories[$article->category] ?? $article->category }}</p>
                    <h2 class="font-bold text-gray-800 mb-2 leading-snug group-hover:text-blue-700 transition">{{ $article->title }}</h2>
                    <p class="text-sm text-gray-500 line-clamp-3">{{ $article->excerpt }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
