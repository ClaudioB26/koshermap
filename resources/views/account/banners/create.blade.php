@extends('layouts.app')

@section('title', __('banners.form_title') . ' - KosherMap')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $tp = \App\Services\Billing\TierPricingService::class;
    $fmt = fn ($n) => '$' . number_format($n, 0, ',', '.');
@endphp
<div class="max-w-2xl mx-auto px-4 py-8" x-data="{ slot: '{{ old('slot', 'top') }}', period: '{{ old('period', '12') }}',
    prices: @json($prices),
    pricesUsd: @json($pricesUsd),
    pct: @json(\App\Services\Billing\TierPricingService::PERIOD_DISCOUNT_PERCENT),
    months: @json(\App\Services\Billing\TierPricingService::PERIOD_MONTHS),
    total() { return Math.round(this.prices[this.slot] * this.months[this.period] * (1 - this.pct[this.period] / 100)); },
    totalUsd() { return Math.round(this.pricesUsd[this.slot] * this.months[this.period] * (1 - this.pct[this.period] / 100) * 100) / 100; },
    money(n) { return '$' + n.toLocaleString('es-AR'); } }">
    <a href="{{ route('account.banners.index') }}" class="text-sm text-blue-600 hover:underline">{{ __('banners.back') }}</a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2 mb-6">📢 {{ __('banners.form_title') }}</h1>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('account.banners.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_name') }} *</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_slot') }} *</label>
            <div class="space-y-2">
                @foreach(['top', 'bottom'] as $s)
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="slot" value="{{ $s }}" x-model="slot" class="text-blue-600">
                    <span>{{ __('banners.slot_' . $s) }} — <span class="text-gray-500">{{ __('banners.per_month', ['price' => number_format($prices[$s], 0, ',', '.')]) }}</span></span>
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_pages') }} *</label>
            <select name="pages" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
                @foreach(['all', 'articles', 'certifiers'] as $p)
                <option value="{{ $p }}" @selected(old('pages', 'all') === $p)>{{ __('banners.pages_' . $p) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_url') }} *</label>
            <input type="url" name="target_url" value="{{ old('target_url') }}" required placeholder="https://"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_image') }} *</label>
            <input type="file" name="image" required accept=".jpg,.jpeg,.png,.webp,.gif"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
            <p class="text-xs text-gray-400 mt-1">{{ __('banners.f_image_help') }}</p>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_alt') }}</label>
            <input type="text" name="alt" value="{{ old('alt') }}" maxlength="255"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_period') }} *</label>
            <select name="period" x-model="period" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
                @foreach(array_reverse($periods) as $p)
                <option value="{{ $p }}">{{ __('plans.period_' . $p, ['pct' => \App\Services\Billing\TierPricingService::PERIOD_DISCOUNT_PERCENT[$p]]) }}</option>
                @endforeach
            </select>
            <p class="text-sm text-gray-700 mt-2">{{ __('banners.total') }}: <strong x-text="money(total()) + ' ARS'"></strong> <span class="text-gray-500">/ US$ <span x-text="totalUsd().toLocaleString('es-AR')"></span></span></p>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('banners.f_payment') }} *</label>
            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="payment_method" value="mercadopago" @checked(old('payment_method', 'mercadopago') === 'mercadopago') class="text-blue-600"> {{ __('banners.pay_mp') }}
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="payment_method" value="transfer" @checked(old('payment_method') === 'transfer') class="text-blue-600"> {{ __('banners.pay_transfer') }}
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="payment_method" value="transfer_usd" @checked(old('payment_method') === 'transfer_usd') class="text-blue-600"> {{ __('banners.pay_transfer_usd') }}
                </label>
            </div>
        </div>

        <p class="text-xs text-gray-500 bg-gray-50 border border-gray-100 rounded-lg p-3">{{ __('banners.review_note') }}</p>

        <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            {{ __('banners.continue') }}
        </button>
    </form>
</div>
@endsection
