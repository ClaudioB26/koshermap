@extends('layouts.app')

@section('title', 'Agregá tu local - KosherMap')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📋 Agregá tu local</h1>
        <p class="text-gray-500 text-sm mt-1">
            Completá los datos de tu local. Nuestro equipo va a revisarlo antes de publicarlo.
        </p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
        <p class="font-semibold mb-1">Revisá los siguientes datos:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @include('places._form', ['action' => route('places.store'), 'submitLabel' => 'Enviar para revisión'])
</div>

@endsection
