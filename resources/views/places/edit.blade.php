@extends('layouts.app')

@section('title', 'Editar ' . $place->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">✏️ Editar {{ $place->name }}</h1>
        @if($place->status === 'approved')
        <p class="text-amber-600 text-sm mt-1">
            ⚠️ Este local ya está publicado. Si lo editás, vuelve a quedar en revisión hasta que lo aprobemos de nuevo.
        </p>
        @else
        <p class="text-gray-500 text-sm mt-1">Corregí los datos y volvé a enviarlo para revisión.</p>
        @endif
    </div>

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

    @include('places._form', [
        'action' => route('account.places.update', $place),
        'method' => 'PUT',
        'submitLabel' => 'Guardar cambios',
    ])
</div>

@endsection
