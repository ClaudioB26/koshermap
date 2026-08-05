@extends('layouts.app')

@section('title', $content['title'] . ' — KosherMap')
@section('meta_description', $content['description'] ?? '')
@section('no_ads', '1')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">{{ $content['title'] }}</span>
    </nav>

    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $content['title'] }}</h1>

    @if(!empty($content['intro']))
        <p class="text-lg text-gray-600 leading-relaxed mb-8 border-l-4 border-blue-500 pl-4">
            {!! $content['intro'] !!}
        </p>
    @endif

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

    <hr class="border-gray-200 mb-8">

    {{-- Formulario de contacto --}}
    <section>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('contact.heading') }}</h2>

        @if(session('contact_sent'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                {{ __('contact.success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.name') }}</label>
                <input type="text" name="name" id="name" required maxlength="255"
                       value="{{ old('name') }}"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.email') }}</label>
                <input type="email" name="email" id="email" required maxlength="255"
                       value="{{ old('email') }}"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('contact.message') }}</label>
                <textarea name="message" id="message" rows="5" required maxlength="2000"
                          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('message') }}</textarea>
                @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-start gap-2">
                <input type="checkbox" name="accepted_privacy" id="accepted_privacy" value="1" required
                       class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="accepted_privacy" class="text-sm text-gray-700">
                    {{ __('contact.accept_privacy') }} <a href="{{ route('pages.privacidad') }}" class="text-blue-600 hover:underline" target="_blank" rel="noopener">{{ __('contact.privacy_policy') }}</a> {{ __('contact.privacy_suffix') }}
                </label>
            </div>
            @error('accepted_privacy')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition">
                {{ __('contact.submit') }}
            </button>
        </form>
    </section>

</div>
@endsection
