@extends('layouts.app')

@section('title', __('forms.acc_title') . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

@php
$statusInfo = [
    'pending'  => ['label' => __('forms.acc_pending_label'), 'badge' => 'bg-yellow-100 text-yellow-700'],
    'approved' => ['label' => __('forms.acc_approved_label'), 'badge' => 'bg-green-100 text-green-700'],
    'rejected' => ['label' => __('forms.acc_rejected_label'), 'badge' => 'bg-red-100 text-red-700'],
];
@endphp

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🏅 {{ __('forms.acc_title') }}</h1>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if(!$certifier)
    <div class="text-center py-20 text-gray-400">
        <p class="text-5xl mb-4">🏅</p>
        <p class="text-lg font-medium">{{ __('forms.acc_none') }}</p>
        <a href="{{ route('certifiers.create') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
            {{ __('forms.acc_register') }}
        </a>
    </div>
    @else
    @php $st = $statusInfo[$certifier->status] ?? $statusInfo['pending']; @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-start gap-2 mb-4">
            <h2 class="text-xl font-bold text-gray-800">{{ $certifier->name }}</h2>
            <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $st['badge'] }}">
                {{ $st['label'] }}
            </span>
        </div>

        @if($certifier->isPending())
        <p class="text-sm text-gray-600 mb-4">
            {{ __('forms.acc_pending_text') }}
        </p>
        @elseif($certifier->isApproved())
        <p class="text-sm text-gray-600 mb-4">
            {{ __('forms.acc_approved_text') }}
        </p>
        <a href="{{ route('account.products') }}"
           class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            📦 {{ __('forms.acc_load_products') }}
        </a>
        <a href="{{ route('certifiers.index') }}"
           class="inline-block ml-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:border-blue-400 transition">
            {{ __('forms.acc_public') }}
        </a>
        <a href="{{ route('account.certifiers.leads') }}"
           class="inline-block ml-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:border-blue-400 transition">
            📥 {{ __('forms.acc_leads', ['count' => $leadsCount]) }}
        </a>
        <a href="{{ route('account.certifiers.plan') }}"
           class="inline-block ml-2 px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 text-sm font-medium rounded-lg hover:bg-amber-100 transition">
            ⭐ {{ __('forms.acc_upgrade', ['tier' => __('plans.tier_' . $certifier->tier)]) }}
        </a>
        @elseif($certifier->isRejected())
        <p class="text-sm text-gray-600 mb-2">{{ __('forms.acc_rejected_text') }}</p>
        @if($certifier->rejection_reason)
        <div class="text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3">
            {{ $certifier->rejection_reason }}
        </div>
        @endif
        @endif

        @if($certifier->isApproved())
        @php
            $badgeSnippet = '<a href="' . url('/certifiers') . '" rel="noopener"><img src="' . url('/images/badge-koshermap.svg') . '" alt="Certificadora en KosherMap" width="220" height="60"></a>';
        @endphp
        <div class="mt-6 pt-6 border-t border-gray-100" x-data="{ copied: false }">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">🏷️ {{ __('forms.acc_badge_title') }}</h3>
            <p class="text-xs text-gray-500 mb-3">{{ __('forms.acc_badge_help') }}</p>
            <img src="{{ asset('images/badge-koshermap.svg') }}" alt="" width="220" height="60" class="mb-3">
            <textarea readonly rows="3" x-ref="snippet" @click="$el.select()"
                      class="w-full text-xs font-mono border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" dir="ltr">{{ $badgeSnippet }}</textarea>
            <button type="button"
                    @click="navigator.clipboard.writeText($refs.snippet.value).then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                    class="mt-2 px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition">
                <span x-show="!copied">{{ __('forms.acc_badge_copy') }}</span>
                <span x-show="copied" x-cloak>{{ __('forms.acc_badge_copied') }}</span>
            </button>
        </div>
        @endif

        <dl class="mt-6 pt-6 border-t border-gray-100 space-y-2 text-sm">
            @if($certifier->rabbi_name)
            <div><dt class="inline text-gray-500">{{ __('forms.acc_rabbi') }}</dt> <dd class="inline text-gray-800">{{ $certifier->rabbi_name }}</dd></div>
            @endif
            @if($certifier->founded_year)
            <div><dt class="inline text-gray-500">{{ __('forms.acc_since') }}</dt> <dd class="inline text-gray-800">{{ $certifier->founded_year }}</dd></div>
            @endif
            <div><dt class="inline text-gray-500">{{ __('forms.acc_coverage') }}</dt> <dd class="inline text-gray-800">{{ $certifier->coverage_description }}</dd></div>
            @if($certifier->website)
            <div><dt class="inline text-gray-500">{{ __('forms.acc_website') }}</dt> <dd class="inline text-gray-800">{{ $certifier->website }}</dd></div>
            @endif
        </dl>
    </div>
    @endif
</div>

@endsection
