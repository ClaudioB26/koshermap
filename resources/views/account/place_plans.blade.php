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
            <p class="text-2xl font-bold text-gray-800 mb-4">${{ number_format($plan['price'], 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">ARS / mes</span></p>

            <ul class="text-sm text-gray-600 space-y-1.5 mb-6 flex-1">
                @if($tierKey === 'destacada_rubro')
                <li>✓ Aparece primero dentro de su rubro (ej: primero entre las carnicerías)</li>
                <li>✓ Badge "Destacado"</li>
                @else
                <li>✓ Todo lo de Destacado por rubro</li>
                <li>✓ Aparece primero en todo el listado, no solo en su rubro</li>
                <li>✓ Badge "Premium"</li>
                @endif
            </ul>

            <div class="space-y-2">
                <form method="POST" action="{{ route('account.places.plan.checkout', $place) }}">
                    @csrf
                    <input type="hidden" name="tier" value="{{ $tierKey }}">
                    <input type="hidden" name="payment_method" value="mercadopago">
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        Pagar con Mercado Pago
                    </button>
                </form>
                <a href="{{ route('account.places.plan.transfer', ['place' => $place->id, 'tier' => $tierKey]) }}"
                   class="block text-center w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    Pagar por transferencia
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-6 text-center">
        El plan se activa apenas confirmamos el pago (automático con Mercado Pago, o al revisar el comprobante de transferencia).
    </p>
</div>
@endsection
