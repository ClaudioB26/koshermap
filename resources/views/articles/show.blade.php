@extends('layouts.app')

@section('title', $article->title . ' — KosherMap')
@section('meta_description', $article->excerpt)

@push('head')
    {{-- hreflang: le dice a Google que esta misma nota existe en estos otros
    idiomas, cada uno con su propia URL, para que le muestre a cada usuario
    la versión que corresponde en los resultados de búsqueda. --}}
    @foreach($article->alternateUrls() as $altLocale => $altUrl)
        <link rel="alternate" hreflang="{{ $altLocale }}" href="{{ $altUrl }}">
    @endforeach
    @if($article->urlFor('es'))
        <link rel="alternate" hreflang="x-default" href="{{ $article->urlFor('es') }}">
    @endif
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <a href="{{ \App\Models\Article::indexUrlFor(app()->getLocale()) }}" class="hover:text-blue-600">{{ \App\Models\Article::sectionLabelFor(app()->getLocale()) }}</a>
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

    @if($related->isNotEmpty())
        <hr class="border-gray-200 my-10">
        <h2 class="text-lg font-bold text-gray-800 mb-4">📰 {{ __('related_articles_heading') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($related as $r)
                <a href="{{ $r->urlFor(app()->getLocale()) ?? route('articles.show', $r->slug) }}"
                   class="block bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
                        {{ \App\Http\Controllers\ArticleController::CATEGORY_LABELS[$r->category] ?? $r->category }}
                    </p>
                    <p class="font-semibold text-gray-800 text-sm mb-1 leading-snug">{{ $r->title }}</p>
                    <p class="text-xs text-gray-500 line-clamp-2">{{ $r->excerpt }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
