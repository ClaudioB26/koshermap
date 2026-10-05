@extends('layouts.app')

@section('title', __('plans.upgrade_title') . ' - ' . $certifier->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('plans.upgrade_heading', ['name' => $certifier->name]) }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ __('plans.current_plan') }}: <strong>{{ __('plans.tier_' . $certifier->tier) }}</strong>
            @if($certifier->tier_expires_at)
            ({{ __('plans.expires_on', ['date' => $certifier->tier_expires_at->format('d/m/Y')]) }})
            @endif
        </p>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        @foreach($plans as $tierKey => $plan)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <h2 class="text-xl font-bold text-blue-800 mb-1">{{ __('plans.tier_' . $tierKey) }}</h2>
            <p class="text-sm text-gray-400 mb-4">${{ number_format($plan['price'], 0, ',', '.') }} {{ __('plans.per_month') }}</p>

            <ul class="text-sm text-gray-600 space-y-1.5 mb-6 flex-1">
                @if($tierKey === 'destacada')
                <li>✓ {{ __('plans.cert_dest_1') }}</li>
                <li>✓ {{ __('plans.cert_dest_2') }}</li>
                @else
                <li>✓ {{ __('plans.cert_pro_1') }}</li>
                <li>✓ {{ __('plans.cert_pro_2') }}</li>
                <li>✓ {{ __('plans.cert_pro_3') }}</li>
                @endif
            </ul>

            <div class="space-y-2">
                <form method="POST" action="{{ route('account.certifiers.plan.checkout') }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="tier" value="{{ $tierKey }}">
                    <input type="hidden" name="payment_method" value="mercadopago">
                    <select name="period" class="w-full text-[13px] border border-gray-300 rounded-lg px-2 py-2 bg-white">
                        @foreach(array_reverse($periods, true) as $periodKey => $periodLabel)
                        <option value="{{ $periodKey }}">
                            @php $total = \App\Services\Billing\TierPricingService::priceFor($plan['price'], $periodKey); $m = \App\Services\Billing\TierPricingService::monthsFor($periodKey); @endphp
                            {{ __('plans.period_' . $periodKey, ['pct' => \App\Services\Billing\TierPricingService::PERIOD_DISCOUNT_PERCENT[$periodKey]]) }} — ${{ number_format($total, 0, ',', '.') }}{{ $m > 1 ? ' ($' . number_format($total / $m, 0, ',', '.') . __('plans.month_short') . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        {{ __('plans.pay_mp') }}
                    </button>
                </form>
                <form method="GET" action="{{ route('account.certifiers.plan.transfer') }}" class="space-y-2">
                    <input type="hidden" name="tier" value="{{ $tierKey }}">
                    <select name="period" class="w-full text-[13px] border border-gray-300 rounded-lg px-2 py-2 bg-white">
                        @foreach(array_reverse($periods, true) as $periodKey => $periodLabel)
                        <option value="{{ $periodKey }}">
                            @php $total = \App\Services\Billing\TierPricingService::priceFor($plan['price'], $periodKey); $m = \App\Services\Billing\TierPricingService::monthsFor($periodKey); @endphp
                            {{ __('plans.period_' . $periodKey, ['pct' => \App\Services\Billing\TierPricingService::PERIOD_DISCOUNT_PERCENT[$periodKey]]) }} — ${{ number_format($total, 0, ',', '.') }}{{ $m > 1 ? ' ($' . number_format($total / $m, 0, ',', '.') . __('plans.month_short') . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                        {{ __('plans.pay_transfer') }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-6 text-center">
        {{ __('plans.footer_note') }}
    </p>
</div>
@endsection
