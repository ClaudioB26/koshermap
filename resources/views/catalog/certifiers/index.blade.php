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

    {{-- Cada certificadora con toda su info en esta misma página (antes tenía
         una ficha propia en /certifiers/{slug} que paginaba su catálogo
         completo; ver doc/plan-adsense.md). --}}
    <div class="max-w-3xl mx-auto space-y-6">
        @foreach($certifiers as $certifier)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-baseline justify-between gap-3 flex-wrap mb-2">
                <h2 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                    {{ $certifier->name }}
                    @if($certifier->tier === 'pro')
                    <span class="text-[10px] font-bold uppercase tracking-wide bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">⭐ Pro</span>
                    @elseif($certifier->tier === 'destacada')
                    <span class="text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Destacada</span>
                    @endif
                </h2>
                <span class="text-xs text-gray-400">{{ number_format($certifier->products_count) }} productos certificados</span>
            </div>

            @if($certifier->about)
            <p class="text-gray-700 text-sm leading-relaxed mb-4">{{ $certifier->about }}</p>
            @endif

            <div class="flex flex-wrap gap-x-5 gap-y-1.5 text-sm text-gray-600 mb-4">
                @if($certifier->contact_email)
                <span>📧 <a href="mailto:{{ $certifier->contact_email }}" class="text-blue-600 hover:underline">{{ $certifier->contact_email }}</a></span>
                @endif
                @if($certifier->phone)
                <span>📞 <a href="tel:{{ $certifier->phone }}" class="text-blue-600 hover:underline">{{ $certifier->phone }}</a></span>
                @endif
                @if($certifier->address)
                <span>📍 {{ $certifier->address }}</span>
                @endif
                @if($certifier->website)
                <span>🔗 <a href="{{ $certifier->website }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">{{ __('Visit website') }}</a></span>
                @endif
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('certifiers.contact', ['slug' => $certifier->slug, 'intent' => 'certify']) }}"
                   class="px-4 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:border-blue-400 hover:text-blue-600 transition">
                    🏭 {{ __('want_to_certify_with', ['name' => $certifier->name]) }}
                </a>
                @if($certifier->products_count > 0)
                <a href="{{ route('search.index', ['certifier' => $certifier->slug]) }}"
                   class="px-4 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    🛒 Buscar productos de {{ $certifier->name }}
                </a>
                @endif
            </div>
        </div>
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
