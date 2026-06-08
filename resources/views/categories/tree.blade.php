@extends('layouts.app')

@section('title', "Categorías - {$certifier->name}")

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
            <span>/</span>
            <a href="{{ route('certifiers.show', $certifier->slug) }}" class="hover:text-blue-600">{{ $certifier->name }}</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Categorías</span>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Categorías de Productos</h1>
                <p class="text-gray-600 mt-2">Explora los productos kosher de {{ $certifier->name }} por categorías</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Certificadora</div>
                <div class="text-lg font-semibold text-blue-600">{{ $certifier->name }}</div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $categories->sum('products_count') }}</div>
                    <div class="text-sm text-gray-600">Total Productos</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $categories->count() }}</div>
                    <div class="text-sm text-gray-600">Categorías Principales</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $categories->sum(function($cat) { return $cat->children->count(); }) }}</div>
                    <div class="text-sm text-gray-600">Subcategorías</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Tree -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Estructura de Categorías</h2>
        </div>
        
        <div class="p-6">
            @if($categories->count() > 0)
                <div class="space-y-4">
                    @foreach($categories as $category)
                        <div class="category-item">
                            <!-- Main Category -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                            {{ $category->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">{{ $category->products_count }} productos</p>
                                    </div>
                                </div>
                                
                                @if($category->children->count() > 0)
                                    <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="toggleCategory('category-{{ $category->id }}')">
                                        <svg class="w-5 h-5 transform transition-transform" id="category-{{ $category->id }}-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Subcategories -->
                            @if($category->children->count() > 0)
                                <div id="category-{{ $category->id }}" class="hidden mt-3 ml-8 space-y-2">
                                    @foreach($category->children as $subcategory)
                                        @if($subcategory->products_count > 0)
                                            <a href="{{ route('certifiers.categories.show', [$certifier->slug, $subcategory->slug]) }}" 
                                               class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-sm transition-all group">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                                    <div>
                                                        <h4 class="font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                                            {{ $subcategory->name }}
                                                        </h4>
                                                        <p class="text-sm text-gray-500">{{ $subcategory->products_count }} productos</p>
                                                    </div>
                                                </div>
                                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay categorías disponibles</h3>
                    <p class="mt-1 text-sm text-gray-500">No se encontraron productos categorizados para esta certificadora.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
function toggleCategory(categoryId) {
    const element = document.getElementById(categoryId);
    const icon = document.getElementById(categoryId + '-icon');
    
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        element.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>
@endsection
@endsection
