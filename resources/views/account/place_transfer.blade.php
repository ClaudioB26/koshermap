@extends('layouts.app')

@section('title', 'Pagar por transferencia - ' . $place->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Pagar plan {{ $plan['label'] }} por transferencia</h1>
    <p class="text-gray-500 text-sm mb-6">
        Monto: <strong>${{ number_format($plan['price'], 0, ',', '.') }} ARS</strong>.
        Transferí a la cuenta de KosherMap (te la pasamos por mail o WhatsApp) y subí el comprobante acá.
        Activamos el plan apenas lo revisemos.
    </p>

    @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('account.places.plan.transfer.store', $place) }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        @csrf
        <input type="hidden" name="tier" value="{{ $tier }}">

        <div>
            <label class="block text-sm text-gray-600 mb-1">Comprobante de transferencia *</label>
            <input type="file" name="proof" required accept=".pdf,.jpg,.jpeg,.png"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <button type="submit"
                class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            Enviar comprobante
        </button>
    </form>
</div>
@endsection
