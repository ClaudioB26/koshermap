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

    <div class="max-w-3xl mx-auto mt-16">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Preguntas frecuentes sobre certificadoras kosher</h2>
        <div class="space-y-5 text-sm text-gray-600 leading-relaxed">
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">¿Qué es la Orthodox Union (OU) y por qué es tan reconocida?</h3>
                <p>La Orthodox Union es la agencia de certificación kosher más grande del mundo, con sede en Nueva York. Supervisa más de un millón de productos en más de 100 países. Su sello —una U dentro de un círculo— es aceptado por prácticamente todas las comunidades judías ortodoxas, conservadoras y reformistas a nivel global. Los productos con OU son los más fáciles de encontrar en supermercados internacionales y son el estándar de referencia para el comercio kosher mundial.</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">¿Todas las certificadoras tienen el mismo nivel de kashrut?</h3>
                <p>No exactamente. Hay distintos niveles de supervisión kasher. El estándar básico es el kosher regular, supervisado por una agencia reconocida. El nivel <strong>Mehadrin</strong> implica un control más estricto en todos los pasos de producción y es requerido por comunidades más observantes. El nivel <strong>Badatz</strong> (principalmente en Israel) es el más exigente. Cada comunidad y cada rabino tiene sus propias preferencias sobre qué certificadoras acepta. Es recomendable consultar con tu autoridad rabínica local si tenés dudas.</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">¿Por qué algunos productos tienen más de un sello kosher?</h3>
                <p>Un mismo producto puede estar certificado por varias agencias para satisfacer distintos mercados. Una empresa que exporta a Israel, Estados Unidos y Argentina puede necesitar el sello del Gran Rabinato israelí, el OU para el mercado norteamericano y Ajdut Kosher para el mercado argentino. Tener múltiples sellos no duplica la kashrut del producto; simplemente amplía los mercados a los que puede acceder legalmente como producto kosher certificado.</p>
            </div>
        </div>
    </div>
@endsection
