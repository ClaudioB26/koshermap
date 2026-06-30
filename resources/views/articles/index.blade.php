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
    <p class="text-gray-500 mb-10">Guías prácticas sobre halajot, kasherización, festividades y vida diaria kosher.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($articles as $article)
            <a href="{{ route('articles.show', $article->slug) }}"
               class="block bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">{{ $article->category }}</p>
                <h2 class="font-bold text-gray-800 mb-2 leading-snug">{{ $article->title }}</h2>
                <p class="text-sm text-gray-500 line-clamp-3">{{ $article->excerpt }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
