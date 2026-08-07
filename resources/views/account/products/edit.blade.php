@extends('layouts.app')

@section('title', 'Editar producto - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">✎ Editar producto</h1>
    </div>

    @include('account.products._form', [
        'action' => route('account.products.update', $product),
        'method' => 'PUT',
        'submitLabel' => 'Guardar cambios',
    ])
</div>

@endsection
