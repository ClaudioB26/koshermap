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

    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">{{ $article->category }}</p>
    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

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
