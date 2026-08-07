@extends('layouts.app')

@section('title', __('catalog.certifiers_title'))
@section('meta_description', __('catalog.certifiers_description'))

@section('content')
    <h1 class="text-3xl font-bold mb-4 text-center text-blue-800">{{ __('Kosher Certifiers') }}</h1>

    <div class="max-w-3xl mx-auto mb-8 text-gray-600 text-sm leading-relaxed">
        <p class="mb-3">{!! __('catalog.certifiers_intro_1') !!}</p>
        <p class="mb-3">{!! __('catalog.certifiers_intro_2') !!}</p>
        <p>{!! __('catalog.certifiers_intro_3') !!}</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($certifiers as $certifier)
        <a href="{{ route('certifiers.show', $certifier->slug) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition text-center border border-gray-100">
            <h2 class="text-xl font-semibold">{{ $certifier->name }}</h2>
            <div class="text-gray-500 text-sm mt-1">{{ $certifier->logo_symbol ?? 'N/A' }}</div>
        </a>
        @endforeach
    </div>

    <div class="max-w-3xl mx-auto mt-10 bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center">
        <p class="font-bold text-gray-800 mb-1">🏅 {{ __('certifier_signup_title') }}</p>
        <p class="text-sm text-gray-500 mb-4">{{ __('certifier_signup_body') }}</p>
        <a href="{{ route('certifiers.create') }}"
           class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition">
            {{ __('certifier_signup_cta') }}
        </a>
    </div>

    <div class="max-w-3xl mx-auto mt-16">
        <h2 class="text-xl font-bold text-gray-800 mb-6">{{ __('catalog.certifiers_faq_heading') }}</h2>
        <div class="space-y-5 text-sm text-gray-600 leading-relaxed">
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">{{ __('catalog.certifiers_faq_1_q') }}</h3>
                <p>{!! __('catalog.certifiers_faq_1_a') !!}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">{{ __('catalog.certifiers_faq_2_q') }}</h3>
                <p>{!! __('catalog.certifiers_faq_2_a') !!}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">{{ __('catalog.certifiers_faq_3_q') }}</h3>
                <p>{!! __('catalog.certifiers_faq_3_a') !!}</p>
            </div>
        </div>
    </div>
@endsection
