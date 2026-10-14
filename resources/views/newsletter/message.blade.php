@extends('layouts.app')

@section('title', $title . ' - KosherMap')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <p class="text-5xl mb-4" aria-hidden="true">✉️</p>
    <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ $title }}</h1>
    <p class="text-gray-600 mb-8">{{ $body }}</p>
    <a href="{{ route('home') }}" class="inline-block px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
        {{ __('newsletter.back_home') }}
    </a>
</div>
@endsection
