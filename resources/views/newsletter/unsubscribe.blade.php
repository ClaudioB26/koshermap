@extends('layouts.app')

@section('title', __('newsletter.unsub_title') . ' - KosherMap')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ __('newsletter.unsub_title') }}</h1>
    <p class="text-gray-600 mb-8">{{ __('newsletter.unsub_body') }}</p>
    <form method="POST" action="{{ route('newsletter.unsubscribe.do', $sub->token) }}">
        @csrf
        <button type="submit" class="px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
            {{ __('newsletter.unsub_button') }}
        </button>
    </form>
</div>
@endsection
