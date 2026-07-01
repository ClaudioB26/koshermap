@extends('layouts.app')

@section('title', 'Países con Productos Kosher - KosherMap')
@section('meta_description', 'Directorio de productos y locales kosher por país: Argentina, Brasil, Israel, Estados Unidos, México, Uruguay, Chile, Francia, Alemania y Reino Unido. Seleccioná tu país para ver resultados locales.')

@section('content')
    <h1 class="text-3xl font-bold mb-4 text-center text-blue-800">{{ __('Countries with Kosher Products') }}</h1>

    <div class="max-w-3xl mx-auto mb-8 text-gray-600 text-sm leading-relaxed text-center">
        <p>KosherMap es un directorio global: cubrimos productos con certificación kosher en más de 10 países. Podés seleccionar tu país para ver los productos disponibles en tu región, filtrados por la certificadora local (por ejemplo, Ajdut Kosher en Argentina, BDK en Brasil o KMD en México). También podés explorar el catálogo internacional de productos con sello OU (Orthodox Union), que se consiguen en supermercados de todo el mundo. Seleccioná un país para establecerlo como tu ubicación predeterminada y ver resultados relevantes para vos.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($countries as $country)
        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 text-center border border-gray-100 flex flex-col items-center">
            <a href="{{ route('countries.show', $country->slug) }}" class="block w-full mb-2">
                <div class="text-gray-500 text-sm mb-1">{{ $country->code }}</div>
                <h2 class="text-xl font-semibold">{{ $country->name }}</h2>
                <p class="text-sm text-gray-600 mt-2">{{ $country->products_count ?? '' }} {{ __('products') }}</p>
            </a>
            <a href="{{ route('set-country', $country->slug) }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium py-1 px-3 rounded-full hover:bg-blue-50 transition">
                📍 {{ __('Use as my location') }}
            </a>
        </div>
        @endforeach
    </div>
@endsection
