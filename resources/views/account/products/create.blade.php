@extends('layouts.app')

@section('title', 'Cargar producto - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">➕ Cargar producto</h1>
        <p class="text-gray-500 text-sm mt-1">Se publica de inmediato en el catálogo de KosherMap.</p>
    </div>

    @include('account.products._form', [
        'action' => route('account.products.store'),
        'method' => 'POST',
        'submitLabel' => 'Publicar producto',
    ])
</div>

@endsection
