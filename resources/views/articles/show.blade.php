@extends('layouts.app')

@section('title', $article->title . ' — KosherMap')
@section('meta_description', $article->excerpt)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <a href="{{ route('articles.index') }}" class="hover:text-blue-600">Artículos</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">{{ $article->title }}</span>
    </nav>

    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">{{ \App\Http\Controllers\ArticleController::CATEGORY_LABELS[$article->category] ?? $article->category }}</p>
    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

    <div class="flex items-center gap-3 text-sm text-gray-500 mb-8">
        <span>Por <a href="{{ route('pages.sobre-nosotros') }}" class="text-blue-600 hover:underline font-medium">Equipo KosherMap</a></span>
        <span>·</span>
        <time datetime="{{ $article->created_at->format('Y-m-d') }}">{{ $article->created_at->translatedFormat('j \d\e F \d\e Y') }}</time>
    </div>

    @if(!empty($article->excerpt))
        <p class="text-lg text-gray-600 leading-relaxed mb-8 border-l-4 border-blue-500 pl-4">
            {{ $article->excerpt }}
        </p>
    @endif

    <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed space-y-3">
        {!! $article->content !!}
    </div>

    {{-- CTA al directorio --}}
    <div class="mt-10 bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center">
        <p class="font-bold text-gray-800 mb-1">¿Buscás productos o lugares kosher certificados?</p>
        <p class="text-sm text-gray-500 mb-4">KosherMap reúne miles de productos y locales kosher de todo el mundo en un solo lugar.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition">🛒 Buscar productos kosher</a>
            <a href="{{ route('places.index') }}" class="px-5 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg font-semibold text-sm hover:bg-blue-50 transition">📍 Encontrar locales</a>
        </div>
    </div>

    @if($related->isNotEmpty())
        <hr class="border-gray-200 my-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Artículos relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($related as $r)
                <a href="{{ route('articles.show', $r->slug) }}"
                   class="block bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                    <p class="font-semibold text-gray-800 text-sm">{{ $r->title }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
