@extends('layouts.app')

@section('title', 'Seleccionar País - Kosher Status')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Selecciona tu País</h1>
        <p class="text-gray-600">
            Personaliza tu experiencia mostrándote contenido kosher relevante para tu ubicación
        </p>
    </div>

    <!-- Current Preference -->
    @if($currentPreference)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-blue-900">Tu preferencia actual</h3>
                    <p class="text-blue-700">
                        País: {{ $currentPreference['country_code'] ?? 'Desconocido' }}
                        @if($currentPreference['source'] === 'auto')
                            <span class="text-sm">(Detectado automáticamente)</span>
                        @else
                            <span class="text-sm">(Seleccionado manualmente)</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('country.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 underline">
                        Restablecer detección automática
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Country Selection -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Selecciona tu país</h2>
            <p class="text-sm text-gray-600 mt-1">
                Mostraremos certificadoras kosher y productos disponibles en tu región
            </p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($countries as $code => $country)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-2xl">{{ $country['flag'] }}</div>
                            @if($currentPreference && $currentPreference['country_code'] === $code)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Actual
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $country['name'] }}</h3>
                        
                        <div class="text-sm text-gray-600 mb-3">
                            <strong>Certificadoras locales:</strong>
                            <ul class="mt-1">
                                @foreach($country['certifiers'] as $certifierSlug)
                                    <li>· {{ ucfirst(str_replace('-', ' ', $certifierSlug)) }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <form action="{{ route('country.set', $code) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                                Seleccionar {{ $country['name'] }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Information -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-3">¿Cómo funciona la geolocalización?</h3>
        
        <div class="space-y-4 text-sm text-gray-600">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 font-semibold text-xs">1</span>
                </div>
                <div>
                    <strong>Detección automática:</strong> Detectamos tu ubicación basándonos en tu dirección IP cuando visitas el sitio por primera vez.
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 font-semibold text-xs">2</span>
                </div>
                <div>
                    <strong>Contenido local:</strong> Mostramos certificadoras y productos kosher disponibles en tu país o región.
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 font-semibold text-xs">3</span>
                </div>
                <div>
                    <strong>Preferencia manual:</strong> Puedes seleccionar manualmente tu país si la detección automática no es correcta.
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                    <span class="text-blue-600 font-semibold text-xs">4</span>
                </div>
                <div>
                    <strong>Persistencia:</strong> Tu preferencia se guarda durante 1 año, pero puedes cambiarla en cualquier momento.
                </div>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                <strong>Privacidad:</strong> Tu ubicación se usa únicamente para mostrar contenido relevante. 
                No compartimos tu información con terceros y puedes desactivar la detección en cualquier momento.
            </p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 underline text-sm">
            Volver al inicio
        </a>
    </div>
</div>

@endsection
