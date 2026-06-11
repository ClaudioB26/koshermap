@extends('layouts.app')

@section('title', 'Mis locales - KosherMap')

@section('content')

@php
$statusInfo = [
    'pending'  => ['label' => 'En revisión', 'badge' => 'bg-yellow-100 text-yellow-700'],
    'approved' => ['label' => 'Publicado',   'badge' => 'bg-green-100 text-green-700'],
    'rejected' => ['label' => 'Rechazado',   'badge' => 'bg-red-100 text-red-700'],
];
@endphp

<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">🏪 Mis locales</h1>
        <p class="text-gray-500 text-sm mt-0.5">Locales que agregaste a KosherMap</p>
    </div>
    <a href="{{ route('places.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shrink-0">
        ➕ Agregar otro local
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
    ✅ {{ session('success') }}
</div>
@endif

@if($places->isEmpty())
<div class="text-center py-20 text-gray-400">
    <p class="text-5xl mb-4">🏪</p>
    <p class="text-lg font-medium">Todavía no agregaste ningún local.</p>
    <a href="{{ route('places.create') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
        Agregar mi local
    </a>
</div>
@else

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($places as $place)
    @php
    $typeInfo = \App\Models\KosherPlace::types()[$place->place_type] ?? \App\Models\KosherPlace::types()['other'];
    $st = $statusInfo[$place->status] ?? $statusInfo['pending'];
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col gap-2">
        <div class="flex justify-between items-start gap-2">
            <h3 class="font-bold text-gray-800 text-base leading-tight">
                {{ $typeInfo['emoji'] }} {{ $place->name }}
            </h3>
            <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $st['badge'] }}">
                {{ $st['label'] }}
            </span>
        </div>

        <div class="text-sm text-gray-500 space-y-0.5">
            <div class="font-medium text-gray-600">
                {{ $place->city->name }}
                @if($place->city->country)
                <span class="text-gray-400">· {{ $place->city->country->name }}</span>
                @endif
            </div>
            @if($place->address)
            <div class="truncate">{{ $place->address }}</div>
            @endif
            @if($place->certifier || $place->certifier_other)
            <div class="text-xs text-gray-400">
                Certificación: {{ $place->certifier->name ?? $place->certifier_other }}
            </div>
            @endif
        </div>

        @if($place->status === 'rejected' && $place->rejection_reason)
        <div class="text-xs text-red-700 bg-red-50 border border-red-100 rounded-lg p-2 mt-1">
            {{ $place->rejection_reason }}
        </div>
        @endif
    </div>
    @endforeach
</div>

@endif

@endsection
