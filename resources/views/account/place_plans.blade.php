@extends('layouts.app')

@section('title', 'Mejorar plan - ' . $place->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Mejorá el plan de {{ $place->name }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            Plan actual: <strong>{{ ucfirst(str_replace('_', ' ', $place->tier)) }}</strong>
            @if($place->tier_expires_at)
            (vence el {{ $place->tier_expires_at->format('d/m/Y') }})
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
            <h2 class="text-xl font-bold text-blue-800 mb-1">{{ $plan['label'] }}</h2>
            <p class="text-sm text-gray-400 mb-4">${{ number_format($plan['price'], 0, ',', '.') }} ARS / mes</p>

            <ul class="text-sm text-gray-600 space-y-1.5 mb-6 flex-1">
                @if($tierKey === 'destacada_rubro')
                <li>✓ Aparece primero dentro de su rubro (ej: primero entre las carnicerías)</li>
                <li>✓ Recuadro destacado en el listado</li>
                @else
                <li>✓ Todo lo de Destacado por rubro</li>
                <li>✓ Aparece primero en todo el listado, no solo en su rubro</li>
                <li>✓ Recuadro reforzado</li>
                @endif
            </ul>

            <div class="space-y-2">
                <form method="POST" action="{{ route('account.places.plan.checkout', $place) }}" class="space-y-2">
                    @csrf
                    <input type="hidden" name="tier" value="{{ $tierKey }}">
                    <input type="hidden" name="payment_method" value="mercadopago">
                    <select name="period" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        @foreach($periods as $periodKey => $periodLabel)
                        <option value="{{ $periodKey }}">
                            {{ $periodLabel }} — ${{ number_format(\App\Services\Billing\TierPricingService::priceFor($plan['price'], $periodKey), 0, ',', '.') }} ARS
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        Pagar con Mercado Pago
                    </button>
                </form>
                <form method="GET" action="{{ route('account.places.plan.transfer', $place) }}" class="space-y-2">
                    <input type="hidden" name="tier" value="{{ $tierKey }}">
                    <select name="period" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        @foreach($periods as $periodKey => $periodLabel)
                        <option value="{{ $periodKey }}">
                            {{ $periodLabel }} — ${{ number_format(\App\Services\Billing\TierPricingService::priceFor($plan['price'], $periodKey), 0, ',', '.') }} ARS
                        </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                        Pagar por transferencia
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-6 text-center">
        El plan se activa apenas confirmamos el pago (automático con Mercado Pago, o al revisar el comprobante de transferencia).
        Si renovás antes de que venza, el tiempo que te quedaba se suma al nuevo período.
    </p>
</div>
@endsection
