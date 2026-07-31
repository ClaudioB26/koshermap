@extends('layouts.app')

@section('title', 'Artículos sobre Kashrut — KosherMap')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">Artículos</span>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 mb-2">Artículos sobre Kashrut</h1>
    <p class="text-gray-500 mb-6">Guías prácticas sobre halajot, kasherización, festividades y vida diaria kosher.</p>

    {{-- Filtro por categoría --}}
    <div class="flex flex-wrap gap-2 mb-10">
        <a href="{{ route('articles.index') }}"
           class="px-4 py-2 rounded-full text-sm font-medium transition {{ !$selectedCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Todos
        </a>
        @foreach($categories as $slug => $label)
            <a href="{{ route('articles.index', ['category' => $slug]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition {{ $selectedCategory === $slug ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($articles as $article)
            <a href="{{ route('articles.show', $article->slug) }}"
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
