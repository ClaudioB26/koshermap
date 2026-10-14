@extends('layouts.app')

@section('title', __('banners.title') . ' - KosherMap')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $badge = [
        'awaiting_payment'   => 'bg-yellow-100 text-yellow-700',
        'awaiting_proof'     => 'bg-yellow-100 text-yellow-700',
        'verifying_transfer' => 'bg-blue-100 text-blue-700',
        'in_review'          => 'bg-blue-100 text-blue-700',
        'active'             => 'bg-green-100 text-green-700',
        'expired'            => 'bg-gray-100 text-gray-600',
        'rejected_payment'   => 'bg-red-100 text-red-700',
        'rejected_review'    => 'bg-red-100 text-red-700',
    ];
@endphp
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-start justify-between gap-3 flex-wrap mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📢 {{ __('banners.title') }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ __('banners.intro') }}</p>
        </div>
        <a href="{{ route('account.banners.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shrink-0">
            ➕ {{ __('banners.new') }}
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif

    @if($orders->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <p class="text-4xl mb-3">📢</p>
        <p>{{ __('banners.empty') }}</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($orders as $order)
        @php $state = $order->state; $b = $order->banner; @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div class="font-bold text-gray-800">{{ $b->name }}</div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $badge[$state] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ __('banners.state_' . $state, ['date' => $b->ends_on?->format('d/m/Y')]) }}
                </span>
            </div>

            <img src="{{ route('banners.image', $b) }}" alt="" loading="lazy"
                 class="mt-3 max-h-24 w-auto rounded-lg border border-gray-200">

            <div class="mt-3 text-xs text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                <span>{{ __('banners.slot_' . $order->slot) }}</span>
                <span>{{ __('plans.period_' . $order->months, ['pct' => \App\Services\Billing\TierPricingService::PERIOD_DISCOUNT_PERCENT[(string) $order->months] ?? 0]) }}</span>
                <span>{{ __('banners.total') }}: ${{ number_format($order->amount, 0, ',', '.') }} ARS</span>
                @if($state === 'active' || $state === 'expired')
                <span>{{ __('banners.impressions') }}: {{ number_format($b->impressions) }}</span>
                <span>{{ __('banners.clicks') }}: {{ number_format($b->clicks) }}</span>
                @endif
            </div>

            @if(in_array($state, ['rejected_payment', 'rejected_review']) && $order->rejection_reason)
            <p class="mt-3 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3">{{ $order->rejection_reason }}</p>
            @endif

            @if($state === 'awaiting_proof')
            <form method="POST" action="{{ route('account.banners.proof', $order) }}" enctype="multipart/form-data"
                  class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                @csrf
                <p class="text-xs text-gray-500">{{ __('banners.transfer_help') }}</p>
                <label class="block text-sm text-gray-600">{{ __('banners.upload_proof') }}</label>
                <input type="file" name="proof" required accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    {{ __('banners.send_proof') }}
                </button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
