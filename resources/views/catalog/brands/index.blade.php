@extends('layouts.app')

@section('title', 'Marcas Kosher - KosherMap')
@section('meta_description', 'Explorá todas las marcas de productos kosher disponibles en KosherMap: alimentos, bebidas y más, certificados por las principales agencias kosher de América Latina, Israel y Estados Unidos.')

@section('content')
    <h1 class="text-3xl font-bold mb-4 text-center text-blue-800">Marcas con Productos Kosher</h1>

    <div class="max-w-3xl mx-auto mb-8 text-gray-600 text-sm leading-relaxed text-center">
        <p>KosherMap reúne productos de cientos de marcas certificadas por agencias kosher reconocidas en todo el mundo. Desde grandes marcas internacionales como las que llevan el sello OU (Orthodox Union) hasta marcas locales de Argentina, Brasil, México, Uruguay e Israel, podés encontrar aquí alimentos, bebidas, condimentos, lácteos, snacks y mucho más con certificación vigente. Hacé click en una marca para ver todos sus productos disponibles con su estado de kashrut (parve, lácteo o cárnico).</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        @foreach($brands as $brand)
        <a href="{{ route('brands.show', $brand->slug) }}"
           class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-center flex flex-col items-center gap-1">
            <p class="font-semibold text-gray-800 text-sm leading-tight">{{ $brand->name }}</p>
            <p class="text-xs text-gray-400">{{ $brand->products_count }} {{ $brand->products_count === 1 ? 'producto' : 'productos' }}</p>
        </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $brands->links() }}
    </div>
@endsection
