@extends('layouts.app')

@section('title', 'Certificadoras Kosher - KosherMap')
@section('meta_description', 'Conocé las principales certificadoras kosher del mundo presentes en KosherMap: Orthodox Union (OU), KMD México, Ajdut Kosher, BDK Brasil, Chile Kosher, Kehila Uruguay y más.')

@section('content')
    <h1 class="text-3xl font-bold mb-4 text-center text-blue-800">{{ __('Kosher Certifiers') }}</h1>

    <div class="max-w-3xl mx-auto mb-8 text-gray-600 text-sm leading-relaxed">
        <p class="mb-3">Una certificación kosher es el aval de una agencia rabbínica que garantiza que un producto cumple con las leyes de kashrut (alimentación judía). Sin este sello, un producto no puede considerarse kosher aunque sus ingredientes parezcan aceptables, ya que la certificación también implica supervisión del proceso de producción, limpieza de líneas y separación de carne y lácteos.</p>
        <p class="mb-3">KosherMap incluye productos certificados por las agencias más reconocidas de América Latina, Estados Unidos e Israel: la <strong>Orthodox Union (OU)</strong> —la más grande del mundo, con presencia en supermercados de más de 100 países—, <strong>KMD México</strong>, <strong>Ajdut Kosher Argentina</strong>, <strong>BDK Brasil</strong>, <strong>Chile Kosher (CK)</strong>, <strong>Kehila Uruguay</strong> y <strong>UK Kosher Latinoamérica</strong>, entre otras.</p>
        <p>Hacé click en una certificadora para ver todos sus productos disponibles en el directorio. Podés filtrar los resultados por categoría de alimento (lácteos, carnes, bebidas, panadería, etc.) para encontrar exactamente lo que necesitás.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($certifiers as $certifier)
        <a href="{{ route('certifiers.show', $certifier->slug) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition text-center border border-gray-100">
            <h2 class="text-xl font-semibold">{{ $certifier->name }}</h2>
            <div class="text-gray-500 text-sm mt-1">{{ $certifier->logo_symbol ?? 'N/A' }}</div>
        </a>
        @endforeach
    </div>
@endsection
