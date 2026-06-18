@extends('layouts.app')

@section('title', $content['title'] . ' — KosherMap')
@section('meta_description', $content['description'] ?? '')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">{{ __('home') ?? 'Inicio' }}</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">{{ $content['title'] }}</span>
    </nav>

    {{-- Título principal --}}
    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $content['title'] }}</h1>

    {{-- Intro --}}
    @if(!empty($content['intro']))
        <p class="text-lg text-gray-600 leading-relaxed mb-8 border-l-4 border-blue-500 pl-4">
            {!! $content['intro'] !!}
        </p>
    @endif

    {{-- Secciones --}}
    @foreach($content['sections'] as $section)
        <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">{{ $section['title'] }}</h2>
            <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed space-y-3">
                {!! $section['body'] !!}
            </div>
        </section>
        @if(!$loop->last)
            <hr class="border-gray-200 mb-8">
        @endif
    @endforeach

</div>
@endsection
