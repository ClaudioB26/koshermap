@extends('layouts.app')

@section('title', 'Rubros de Alimentos Kosher - KosherMap')
@section('meta_description', 'Explorá todas las categorías de alimentos kosher: lácteos, carnes, bebidas, panadería, snacks y más. Cada rubro incluye productos certificados por las principales agencias kosher del mundo.')

@section('content')
    <h1 class="text-3xl font-bold mb-4 text-center text-blue-800">{{ __('Food Categories') }}</h1>

    <div class="max-w-3xl mx-auto mb-8 text-gray-600 text-sm leading-relaxed text-center">
        <p>KosherMap organiza su catálogo de más de 6.000 productos kosher en rubros y subrubros para facilitar tu búsqueda. Encontrá alimentos certificados por categoría: desde lácteos y carnes hasta panadería, bebidas, snacks y productos de limpieza. Cada categoría muestra el sello de la certificadora (OU, KMD, Ajdut, BDK y otras) para que puedas verificar la kashrut del producto antes de comprarlo.</p>
    </div>
    
    <div class="space-y-12">
        @foreach($categories as $category)
        <div class="bg-gray-50 p-6 rounded-xl">
            <a href="{{ route('categories.show', $category->slug) }}" class="block mb-4">
                <h2 class="text-2xl font-bold text-gray-800 hover:text-blue-600 transition flex items-center gap-2">
                    {{ $category->name }}
                    <span class="text-sm font-normal text-gray-500 bg-gray-200 px-2 py-1 rounded-full">{{ __('View all') }}</span>
                </h2>
            </a>
            
            @if($category->children->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($category->children as $child)
                <a href="{{ route('categories.show', $child->slug) }}" class="bg-white px-4 py-3 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200 text-gray-700 hover:text-blue-600 font-medium text-sm">
                    {{ $child->name }}
                </a>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 italic text-sm">{{ __('No subcategories') }}</p>
            @endif
        </div>
        @endforeach
    </div>
@endsection
