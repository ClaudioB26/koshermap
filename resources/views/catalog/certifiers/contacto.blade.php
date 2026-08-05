<?php /** @var \App\Models\Certifier $certifier */ ?>
@extends('layouts.app')

@section('title', __('Contact') . ' ' . $certifier->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">

    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600">KosherMap</a>
        <span class="mx-2">›</span>
        <a href="{{ route('certifiers.show', $certifier->slug) }}" class="hover:text-blue-600">{{ $certifier->name }}</a>
        <span class="mx-2">›</span>
        <span class="text-gray-700">{{ __('Contact') }}</span>
    </nav>

    @if($intent === 'certify')
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('want_to_certify_with', ['name' => $certifier->name]) }}</h1>
        <p class="text-gray-600 text-sm mb-8">{{ __('certify_cta_body') }}</p>
    @else
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Contact') }} {{ $certifier->name }}</h1>
        <p class="text-gray-600 text-sm mb-8">{{ __('certifier_general_contact_body') }}</p>
    @endif

    @if($certifier->contact_email || $certifier->phone || $certifier->address || $certifier->hours || $certifier->website)
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
    @else
        <p class="text-sm text-gray-500">
            {{ $certifier->name }} {{ __('no_direct_contact_yet') }}
        </p>
    @endif

</div>
@endsection
