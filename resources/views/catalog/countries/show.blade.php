@extends('layouts.app')

@section('title', $country->name . ' - KosherMap')

@section('content')

@php $tab = request('tab', 'productos'); @endphp

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $country->name }}</h1>
        <p class="text-gray-500 text-sm mt-0.5">Productos y locales kosher</p>
    </div>
    <a href="{{ route('countries.index') }}" class="text-blue-600 hover:underline text-sm">
        ← {{ __('View all countries') }}
    </a>
</div>

{{-- Tabs --}}
<div class="flex border-b border-gray-200 mb-6">
    <a href="{{ request()->fullUrlWithQuery(['tab' => 'productos']) }}"
       class="px-6 py-3 font-semibold text-sm border-b-2 transition
              {{ $tab === 'productos' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        🛒 Productos
        @if($products->total())
        <span class="ml-1.5 text-xs {{ $tab === 'productos' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full">
            {{ number_format($products->total()) }}
        </span>
        @endif
    </a>
    <a href="{{ request()->fullUrlWithQuery(['tab' => 'lugares']) }}"
       class="px-6 py-3 font-semibold text-sm border-b-2 transition
              {{ $tab === 'lugares' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        📍 Lugares
        @if($places->count())
        <span class="ml-1.5 text-xs {{ $tab === 'lugares' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full">
            {{ $places->count() }}
        </span>
        @endif
    </a>
</div>

{{-- TAB: PRODUCTOS --}}
@if($tab === 'productos')

    @if($certifiers->isNotEmpty())
    <div class="mb-8 p-5 bg-white rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">{{ __('Certifiers in this region') }}</h2>
        <div class="flex gap-3 flex-wrap">
            @foreach($certifiers as $cert)
            <a href="{{ route('certifiers.show', $cert->slug) }}"
               class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-blue-50 text-blue-800 hover:bg-blue-100 transition border border-blue-200">
                {{ $cert->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($products->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-lg">No hay productos registrados para {{ $country->name }}.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($products as $product)
        <div class="bg-white p-4 rounded-xl shadow-sm hover:shadow-md transition border border-gray-100">
            @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-40 object-contain mb-4 rounded-lg">
            @endif
            <h3 class="font-bold text-lg mb-1 text-gray-800">{{ $product->name }}</h3>
            <p class="text-gray-500 text-sm mb-3">{{ $product->brand->name ?? __('Unknown Brand') }}</p>
            <div class="flex justify-between items-center">
                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                    {{ $product->kosher_status }}
                </span>
                <a href="{{ route('products.show', $product->slug) }}"
                   class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                    {{ __('View details') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
    @endif

{{-- TAB: LUGARES --}}
@else

    {{-- Filtros por tipo --}}
    @if($placeTypes->count() > 1)
    @php
    $typeLabels = [
        'restaurant'    => '🍽️ Restaurantes',
        'bakery'        => '🥐 Panaderías',
        'bar'           => '🍷 Bares',
        'confectionery' => '☕ Cafeterías',
        'temple'        => '🕍 Sinagogas',
        'school'        => '🏫 Escuelas',
        'other'         => '📍 Otros',
    ];
    @endphp
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'lugares', 'place_type' => null]) }}"
           class="px-3 py-1.5 rounded-full text-sm font-medium border transition
                  {{ !$placeType ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
            Todos ({{ $placeTypes->sum() }})
        </a>
        @foreach($placeTypes as $type => $count)
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'lugares', 'place_type' => $type]) }}"
           class="px-3 py-1.5 rounded-full text-sm font-medium border transition
                  {{ $placeType === $type ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
            {{ $typeLabels[$type] ?? $type }} ({{ $count }})
        </a>
        @endforeach
    </div>
    @endif

    @if($places->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-4xl mb-4">📍</p>
        <p class="text-lg">No hay locales kosher registrados para {{ $country->name }}.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($places as $place)
        @php
        $typeBadge = [
            'restaurant'    => ['bg-orange-100 text-orange-700', '🍽️'],
            'bakery'        => ['bg-yellow-100 text-yellow-700', '🥐'],
            'bar'           => ['bg-purple-100 text-purple-700', '🍷'],
            'confectionery' => ['bg-pink-100 text-pink-700',   '☕'],
            'temple'        => ['bg-blue-100 text-blue-700',   '🕍'],
            'school'        => ['bg-green-100 text-green-700', '🏫'],
            'other'         => ['bg-gray-100 text-gray-600',   '📍'],
        ][$place->place_type] ?? ['bg-gray-100 text-gray-600', '📍'];
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-2 hover:shadow-md transition">
            <div class="flex justify-between items-start gap-2">
                <h3 class="font-bold text-gray-800 text-base leading-tight">{{ $place->name }}</h3>
                <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $typeBadge[0] }}">
                    {{ $typeBadge[1] }}
                </span>
            </div>

            @if($place->google_rating)
            <div class="flex items-center gap-1.5 text-sm">
                <span class="text-yellow-400">★</span>
                <span class="font-semibold text-gray-700">{{ number_format($place->google_rating, 1) }}</span>
                @if($place->google_reviews_count)
                <span class="text-gray-400">({{ number_format($place->google_reviews_count) }})</span>
                @endif
            </div>
            @endif

            <div class="text-sm text-gray-500 space-y-0.5">
                <div class="font-medium text-gray-600">{{ $place->city->name }}</div>
                @if($place->address)
                <div class="truncate">{{ $place->address }}</div>
                @endif
            </div>

            <div class="flex gap-3 mt-auto pt-2 text-xs">
                @if($place->phone)
                <a href="tel:{{ $place->phone }}" class="text-blue-600 hover:underline">📞 {{ $place->phone }}</a>
                @endif
                @if($place->website)
                <a href="{{ $place->website }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline truncate">
                    🌐 {{ __('Website') }}
                </a>
                @endif
            </div>

            <div x-data="{ open: false }" class="pt-1 border-t border-gray-100 mt-1">
                <button @click="open = !open" type="button"
                        class="text-xs text-gray-400 hover:text-red-500 transition flex items-center gap-1">
                    ⚑ Reportar problema
                </button>
                <div x-show="open" x-transition class="mt-2">
                    <form method="POST" action="{{ route('places.report', $place) }}" class="space-y-2">
                        @csrf
                        <select name="reason" required
                                class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white">
                            <option value="">— Motivo —</option>
                            @foreach(\App\Models\Report::reasonsPlace() as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                        <textarea name="observation" rows="2" placeholder="Observación (opcional)"
                                  class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white resize-none"></textarea>
                        <input type="email" name="email" placeholder="Tu email (opcional)"
                               class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white">
                        <div class="flex gap-2">
                            <button type="submit"
                                    class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">
                                Enviar
                            </button>
                            <button type="button" @click="open = false"
                                    class="text-xs text-gray-400 hover:text-gray-600">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

@endif

@endsection
