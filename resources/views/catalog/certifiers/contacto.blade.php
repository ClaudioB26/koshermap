<?php /** @var \App\Models\Certifier $certifier */ ?>
@extends('layouts.app')

@section('title', __('Contact') . ' ' . $certifier->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">

    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <a href="{{ route('certifiers.index') }}" class="hover:text-blue-600">{{ $certifier->name }}</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">{{ __('Contact') }}</span>
    </nav>

    @if($intent === 'certify')
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('want_to_certify_with', ['name' => $certifier->name]) }}</h1>
        <p class="text-gray-600 text-sm mb-8">{{ __('certify_cta_body') }}</p>

        @if(session('lead_sent'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800 mb-6">
            ¡Gracias! Le avisamos a {{ $certifier->name }} que querés certificar tu empresa. Te van a contactar directamente.
        </div>
        @else
        @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('certifiers.contact.store', $certifier->slug) }}"
              class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            @csrf
            {{-- Honeypot: campo oculto por CSS, invisible para personas. Si un bot
                 lo completa, el backend descarta el envio sin guardar nada. --}}
            <div style="position:absolute; left:-9999px;" aria-hidden="true">
                <label>No completar<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Tu nombre *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Empresa *</label>
                <input type="text" name="company" value="{{ old('company') }}" required
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">¿Qué producto querés certificar?</label>
                <input type="text" name="product_type" value="{{ old('product_type') }}"
                       placeholder="Ej: galletitas, lácteos, bebidas..."
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Contanos más (opcional)</label>
                <textarea name="message" rows="3"
                          class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">{{ old('message') }}</textarea>
            </div>
            <button type="submit"
                    class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                Enviar a {{ $certifier->name }}
            </button>
        </form>

        {{-- Chico y debajo a proposito: que la gente prefiera enviar el
             formulario (que si genera un lead) en vez de ir directo al
             contacto. --}}
        <p class="text-center mt-3">
            <a href="{{ route('certifiers.more-info', $certifier->slug) }}" class="text-xs text-gray-400 hover:text-gray-600 hover:underline">
                Ver información de contacto de {{ $certifier->name }}
            </a>
        </p>
        @endif
    @else
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Contact') }} {{ $certifier->name }}</h1>
        <p class="text-gray-600 text-sm mb-8">{{ __('certifier_general_contact_body') }}</p>
    @endif

    @if($intent !== 'certify' && ($certifier->contact_email || $certifier->phone || $certifier->address || $certifier->hours || $certifier->website))
        <div class="p-5 bg-blue-50 border border-blue-100 rounded-xl">
            <h2 class="font-semibold text-blue-900 mb-3">{{ $certifier->name }}</h2>
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
        </div>
    @elseif($intent !== 'certify')
        <p class="text-sm text-gray-500">
            {{ $certifier->name }} {{ __('no_direct_contact_yet') }}
        </p>
    @endif

</div>
@endsection
