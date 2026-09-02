@extends('layouts.app')

@section('title', __('Certifier') . ': ' . $certifier->name . ' - KosherMap')
{{-- Datos de contacto directo (mail/telefono/web): a proposito noindex.
     Si Google la indexa, la gente entra por el buscador directo al dato de
     contacto sin pasar por el formulario de "querer certificar" (que es el
     que captura el lead), salteando justo lo que se busca evitar. --}}
@section('robots', 'noindex, follow')

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
            <p class="text-sm text-gray-500">{{ $certifier->name }} {{ __('no_direct_contact_yet') }}</p>
        @endif
    </div>

    @if($productsCount > 0)
    <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 text-center mb-6">
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

    @if($relatedArticles->isNotEmpty())
    @include('partials.related_articles_sidebar')
    @endif
@endsection
