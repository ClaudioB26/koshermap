@extends('layouts.app')

@section('title', 'Mi certificadora - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

@php
$statusInfo = [
    'pending'  => ['label' => 'En revisión', 'badge' => 'bg-yellow-100 text-yellow-700'],
    'approved' => ['label' => 'Aprobada',    'badge' => 'bg-green-100 text-green-700'],
    'rejected' => ['label' => 'Rechazada',   'badge' => 'bg-red-100 text-red-700'],
];
@endphp

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🏅 Mi certificadora</h1>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if(!$certifier)
    <div class="text-center py-20 text-gray-400">
        <p class="text-5xl mb-4">🏅</p>
        <p class="text-lg font-medium">Todavía no diste de alta tu certificadora.</p>
        <a href="{{ route('certifiers.create') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
            Dar de alta mi certificadora
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
            Tu solicitud está en revisión. Nuestro equipo la investiga antes de aprobarla —
            suele tomar alrededor de una semana. Te vamos a contactar por email.
        </p>
        @elseif($certifier->isApproved())
        <p class="text-sm text-gray-600 mb-4">
            ¡Tu certificadora ya está publicada en KosherMap! Ahora podés cargar los productos
            que certificás directamente desde acá.
        </p>
        <a href="{{ route('account.products') }}"
           class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            📦 Cargar mis productos
        </a>
        <a href="{{ route('certifiers.index') }}"
           class="inline-block ml-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:border-blue-400 transition">
            Ver mi ficha pública
        </a>
        <a href="{{ route('account.certifiers.plan') }}"
           class="inline-block ml-2 px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 text-sm font-medium rounded-lg hover:bg-amber-100 transition">
            ⭐ Mejorar plan ({{ ucfirst($certifier->tier) }})
        </a>
        @elseif($certifier->isRejected())
        <p class="text-sm text-gray-600 mb-2">Tu solicitud no fue aprobada.</p>
        @if($certifier->rejection_reason)
        <div class="text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3">
            {{ $certifier->rejection_reason }}
        </div>
        @endif
        @endif

        <dl class="mt-6 pt-6 border-t border-gray-100 space-y-2 text-sm">
            @if($certifier->rabbi_name)
            <div><dt class="inline text-gray-500">Rabino a cargo:</dt> <dd class="inline text-gray-800">{{ $certifier->rabbi_name }}</dd></div>
            @endif
            @if($certifier->founded_year)
            <div><dt class="inline text-gray-500">Certifica desde:</dt> <dd class="inline text-gray-800">{{ $certifier->founded_year }}</dd></div>
            @endif
            <div><dt class="inline text-gray-500">Cobertura:</dt> <dd class="inline text-gray-800">{{ $certifier->coverage_description }}</dd></div>
            @if($certifier->website)
            <div><dt class="inline text-gray-500">Sitio web:</dt> <dd class="inline text-gray-800">{{ $certifier->website }}</dd></div>
            @endif
        </dl>
    </div>
    @endif
</div>

@endsection
