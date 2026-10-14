@extends('layouts.admin-panel')
@section('title', 'Pedidos de banners')
@section('content')
@php
    $labels = [
        'awaiting_payment'   => ['Esperando pago (Mercado Pago)', 'bg-yellow-100 text-yellow-700'],
        'awaiting_proof'     => ['Falta comprobante', 'bg-yellow-100 text-yellow-700'],
        'verifying_transfer' => ['Verificar transferencia', 'bg-blue-100 text-blue-700'],
        'in_review'          => ['Revisar imagen', 'bg-blue-100 text-blue-700'],
        'active'             => ['Publicado', 'bg-green-100 text-green-700'],
        'expired'            => ['Vencido', 'bg-gray-100 text-gray-600'],
        'rejected_payment'   => ['Pago rechazado', 'bg-red-100 text-red-700'],
        'rejected_review'    => ['Imagen rechazada', 'bg-red-100 text-red-700'],
    ];
@endphp
<div>
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h1 class="text-2xl font-bold text-gray-800">🧾 Pedidos de banners</h1>
        <a href="{{ route('admin.banners.index') }}" class="text-sm text-blue-600 hover:underline">← Banners</a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-400">Todavía no hay pedidos.</div>
    @else
    <div class="space-y-4">
        @foreach($orders as $o)
        @php $st = $o->state; $b = $o->banner; @endphp
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                    <div class="font-bold text-gray-800">{{ $b->name }}</div>
                    <div class="text-xs text-gray-500">{{ $o->user->name }} · {{ $o->user->email }} · {{ $o->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $labels[$st][1] ?? 'bg-gray-100 text-gray-600' }}">{{ $labels[$st][0] ?? $st }}</span>
            </div>

            <img src="{{ route('banners.image', $b) }}" alt="" class="mt-3 max-h-28 w-auto border border-gray-200 rounded">
            <div class="mt-2 text-xs text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                <span>{{ \App\Models\Banner::SLOTS[$o->slot] ?? $o->slot }}</span>
                <span>{{ \App\Models\Banner::PAGES[$b->pages] ?? $b->pages }}</span>
                <span>{{ $o->months }} mes(es)</span>
                <span>${{ number_format($o->amount, 0, ',', '.') }} ARS</span>
                <span>{{ $o->payment_method === 'transfer' ? 'Transferencia' : 'Mercado Pago' }}</span>
                <span>Destino: <a href="{{ $b->target_url }}" target="_blank" rel="noopener nofollow" class="text-blue-600 hover:underline">{{ \Illuminate\Support\Str::limit($b->target_url, 50) }}</a></span>
                @if($o->transfer_proof_path)
                <a href="{{ route('admin.files.show', ['path' => $o->transfer_proof_path]) }}" target="_blank" class="text-blue-600 hover:underline">📄 Ver comprobante</a>
                @endif
            </div>

            <div class="mt-3 flex flex-wrap gap-2 items-start">
                {{-- Pago por transferencia --}}
                @if($o->payment_method === 'transfer' && $o->payment_status === 'pending' && $o->transfer_proof_path)
                <form method="POST" action="{{ route('admin.banner-orders.approve-payment', $o) }}">@csrf
                    <button class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700">✓ Aprobar pago</button>
                </form>
                <form method="POST" action="{{ route('admin.banner-orders.reject-payment', $o) }}" class="flex gap-1">@csrf
                    <input name="reason" placeholder="Motivo (opcional)" class="text-xs border border-gray-300 rounded px-2 py-1">
                    <button class="px-3 py-1.5 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700">✗ Rechazar pago</button>
                </form>
                @endif

                {{-- Revision de la imagen (cuando ya esta pagado) --}}
                @if($o->review_status === 'pending' && $o->payment_status === 'approved')
                <form method="POST" action="{{ route('admin.banner-orders.approve-review', $o) }}">@csrf
                    <button class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700">✓ Aprobar y publicar</button>
                </form>
                <form method="POST" action="{{ route('admin.banner-orders.reject-review', $o) }}" class="flex gap-1">@csrf
                    <input name="reason" placeholder="Motivo (se envía al anunciante)" class="text-xs border border-gray-300 rounded px-2 py-1">
                    <button class="px-3 py-1.5 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700">✗ Rechazar imagen</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
