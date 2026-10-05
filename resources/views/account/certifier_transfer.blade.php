@extends('layouts.app')

@section('title', __('plans.transfer_title') . ' - ' . $certifier->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ __('plans.transfer_heading', ['plan' => __('plans.tier_' . $tier)]) }}</h1>
    <p class="text-gray-500 text-sm mb-6">
        {{ __('plans.period') }}: <strong>{{ __('plans.period_' . $period, ['pct' => \App\Services\Billing\TierPricingService::PERIOD_DISCOUNT_PERCENT[$period]]) }}</strong> — {{ __('plans.amount') }}: <strong>${{ number_format($amount, 0, ',', '.') }} ARS</strong>.
        {{ __('plans.transfer_intro') }}
    </p>

    @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('account.certifiers.plan.transfer.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        @csrf
        <input type="hidden" name="tier" value="{{ $tier }}">
        <input type="hidden" name="period" value="{{ $period }}">

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('plans.proof_label') }} *</label>
            <input type="file" name="proof" required accept=".pdf,.jpg,.jpeg,.png"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <button type="submit"
                class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            {{ __('plans.send_proof') }}
        </button>
    </form>
</div>
@endsection
