@extends('layouts.app')

@section('title', __('Certifier') . ': ' . $certifier->name . ' - KosherMap')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-800">{{ __('Certifier') }}: {{ $certifier->name }}</h1>
        <a href="{{ route('certifiers.index') }}" class="text-blue-600 hover:underline">{{ __('View all certifiers') }}</a>
    </div>

    @if($certifier->about)
    <div class="mb-6 p-5 bg-blue-50 border border-blue-100 rounded-xl">
        <p class="text-gray-700 text-sm leading-relaxed">{{ $certifier->about }}</p>
    </div>
    @endif

    <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-3">
        <a href="{{ route('certifiers.contact', ['slug' => $certifier->slug, 'intent' => 'certify']) }}"
           class="flex flex-col items-center text-center gap-1 bg-white border border-gray-200 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition">
            <span class="text-2xl">🏭</span>
            <span class="font-semibold text-sm text-gray-800">{{ __('want_to_certify_with', ['name' => $certifier->name]) }}</span>
            <span class="text-xs text-gray-500">{{ __('certify_cta_body') }}</span>
        </a>
        <a href="#productos-certificados"
           class="flex flex-col items-center text-center gap-1 bg-white border border-gray-200 rounded-xl p-4 hover:border-blue-400 hover:shadow-md transition">
            <span class="text-2xl">🛒</span>
            <span class="font-semibold text-sm text-gray-800">{{ __('View certified products') }}</span>
        </a>
    </div>

    <div class="mb-8 p-5 bg-white border border-gray-200 rounded-xl">
        <h2 class="text-lg font-bold text-gray-800 mb-3">✉️ {{ __('Contact') }} {{ $certifier->name }}</h2>
        @if($certifier->contact_email || $certifier->phone || $certifier->address || $certifier->hours || $certifier->website)
            <ul class="space-y-1.5 text-sm text-gray-700">
                @if($certifier->contact_email)
                <li>📧 <a href="mailto:{{ $certifier->contact_email }}" class="text-blue-600 hover:underline">{{ $certifier->contact_email }}</a></li>
                @endif
                @if($certifier->phone)
                <li>📞 <a href="tel:{{ $certifier->phone }}" class="text-blue-600 hover:underline">{{ $certifier->phone }}</a></li>
                @endif
                @if($certifier->address)
                <li>📍 {{ $certifier->address }}</li>
                @endif
                @if($certifier->hours)
                <li>🕒 {{ $certifier->hours }}</li>
                @endif
                @if($certifier->website)
                <li>🔗 <a href="{{ $certifier->website }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">{{ __('Visit website') }}</a></li>
                @endif
            </ul>
        @else
            <a href="{{ route('certifiers.contact', $certifier->slug) }}" class="text-sm text-blue-600 hover:underline">
                {{ __('Contact') }} {{ $certifier->name }} →
            </a>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1 min-w-0" id="productos-certificados">
            @if($productsCount > 0)
            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 text-center">
                <p class="text-gray-700 mb-3">
                    {{ $certifier->name }} certifica <strong>{{ number_format($productsCount) }}</strong>
                    {{ $productsCount === 1 ? 'producto' : 'productos' }} en nuestra base.
                </p>
                <a href="{{ route('search.index', ['certifier' => $certifier->slug]) }}"
                   class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition">
                    🛒 Buscar productos de {{ $certifier->name }}
                </a>
            </div>
            @endif
        </div>

        @if($relatedArticles->isNotEmpty())
        <aside class="hidden lg:block lg:w-[26rem] shrink-0">
            <div class="sticky top-20">
                @include('partials.related_articles_sidebar')
            </div>
        </aside>
        @endif
    </div>

    <div class="lg:hidden mt-6">
        @include('partials.related_articles_sidebar')
    </div>
@endsection
