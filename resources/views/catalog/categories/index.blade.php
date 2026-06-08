@extends('layouts.app')

@section('title', 'Rubros - KosherStatus')

@section('content')
    <h1 class="text-3xl font-bold mb-8 text-center text-blue-800">{{ __('Food Categories') }}</h1>
    
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
