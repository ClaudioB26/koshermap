<?php /** @var \App\Models\Certifier $certifier */ ?>
@extends('layouts.app')

@section('title', __('contact.heading') . ' ' . $certifier->name . ' - KosherMap')
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

    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Want to certify your business?') }} {{ $certifier->name }}</h1>
    <p class="text-gray-600 text-sm mb-8">{{ __('certify_cta_body') }}</p>

    @if($certifier->contact_email || $certifier->phone || $certifier->hours)
        <div class="mb-8 p-5 bg-blue-50 border border-blue-100 rounded-xl">
            <h2 class="font-semibold text-blue-900 mb-3">{{ $certifier->name }}</h2>
            <ul class="space-y-1.5 text-sm text-gray-700">
                @if($certifier->contact_email)
                <li>📧 <a href="mailto:{{ $certifier->contact_email }}?subject={{ urlencode(__('Want to certify your business?').' - '.$certifier->name) }}" class="text-blue-600 hover:underline">{{ $certifier->contact_email }}</a></li>
                @endif
                @if($certifier->phone)
                <li>📞 <a href="tel:{{ $certifier->phone }}" class="text-blue-600 hover:underline">{{ $certifier->phone }}</a></li>
                @endif
                @if($certifier->hours)
                <li>🕒 {{ $certifier->hours }}</li>
                @endif
            </ul>
        </div>
    @else
        <p class="text-sm text-gray-500 mb-6">
            {{ $certifier->name }} {{ __('no_direct_contact_yet') }}
        </p>

        @if(session('contact_sent'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                {{ __('contact.success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('certifiers.contact.store', $certifier->slug) }}" class="space-y-4">
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
    @endif

</div>
@endsection
