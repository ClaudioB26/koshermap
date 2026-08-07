@extends('layouts.app')

@section('title', 'Mis productos - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">📦 Mis productos</h1>
        <p class="text-gray-500 text-sm mt-0.5">Productos certificados por {{ $certifier->name }}</p>
    </div>
    <a href="{{ route('account.products.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shrink-0">
        ➕ Cargar producto
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
    ✅ {{ session('success') }}
</div>
@endif

@if($products->isEmpty())
<div class="text-center py-20 text-gray-400">
    <p class="text-5xl mb-4">📦</p>
    <p class="text-lg font-medium">Todavía no cargaste ningún producto.</p>
    <a href="{{ route('account.products.create') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
        Cargar mi primer producto
    </a>
</div>
@else

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Producto</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Marca</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Categoría</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Estado</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Visible</th>
                <th class="text-right px-4 py-3 font-semibold text-gray-600">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($products as $product)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->brand->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->category->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                        {{ $product->kosher_status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    @if($product->is_active)
                    <span class="text-green-600 text-xs font-medium">● Visible</span>
                    @else
                    <span class="text-gray-400 text-xs font-medium">○ Oculto</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2 justify-end">
                        <a href="{{ route('account.products.edit', $product) }}"
                           class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-lg hover:bg-gray-200 transition">
                            ✎ Editar
                        </a>
                        <form method="POST" action="{{ route('account.products.toggle', $product) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1 text-xs rounded-lg transition
                                           {{ $product->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                {{ $product->is_active ? 'Ocultar' : 'Publicar' }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>

@endif

@endsection
