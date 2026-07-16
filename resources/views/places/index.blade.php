@extends('layouts.app')

@section('title', 'Lugares Kosher - KosherMap')
@section('meta_description', 'Encontrá restaurantes, sinagogas, panaderías, carnicerías y comunidades kosher en tu ciudad. Directorio global de lugares kosher verificados por país.')

@section('content')

<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">📍 Lugares Kosher</h1>
        <p class="text-gray-500 text-sm mt-0.5">Restaurantes, panaderías, sinagogas y más</p>
    </div>
    <a href="{{ route('places.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shrink-0">
        ➕ Agregar mi local
    </a>
</div>

<div class="max-w-3xl mb-8 text-gray-600 text-sm leading-relaxed">
    <p class="mb-3">Encontrar un lugar kosher confiable en una ciudad nueva no siempre es fácil, sobre todo si estás de viaje o te acabás de mudar. Acá reunimos restaurantes, panaderías, carnicerías, sinagogas y comunidades de distintos países, con rating de Google, dirección y contacto para que no tengas que armar la búsqueda de cero cada vez.</p>
    <p>Los locales se relevan a través de Google Maps y se revisan con la comunidad de moderadores antes de aparecer en el directorio. Por default mostramos sinagogas y comunidades de orientación ortodoxa, pero podés ampliar el filtro para ver también conservadoras y reformistas. Si encontrás un lugar cerrado, con datos viejos, o querés sumar uno que falta, usá el botón de reportar en cada tarjeta o "Agregar mi local" arriba.</p>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 items-center">

    {{-- Filtro país --}}
    <form method="GET" action="{{ route('places.index') }}" class="flex items-center gap-2">
        @if($query) <input type="hidden" name="query" value="{{ $query }}"> @endif
        @if($placeType) <input type="hidden" name="place_type" value="{{ $placeType }}"> @endif
        <select name="country" onchange="this.form.submit()"
                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">🌍 Todos los países</option>
            @foreach($countries as $c)
            <option value="{{ $c->slug }}" {{ $countrySlug === $c->slug ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
            @endforeach
        </select>
    </form>

    {{-- Filtros por tipo --}}
    @php $placeTypeInfo = \App\Models\KosherPlace::types(); @endphp
    <div class="flex flex-wrap gap-2">
        <a href="{{ request()->fullUrlWithQuery(['place_type' => null]) }}"
           class="px-3 py-1.5 rounded-full text-sm font-medium border transition
                  {{ !$placeType ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
            Todos
        </a>
        @foreach($placeTypes as $type => $count)
        @if(isset($placeTypeInfo[$type]))
        <a href="{{ request()->fullUrlWithQuery(['place_type' => $type]) }}"
           class="px-3 py-1.5 rounded-full text-sm font-medium border transition
                  {{ $placeType === $type ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:border-blue-400' }}">
            {{ $placeTypeInfo[$type]['emoji'] }} {{ $placeTypeInfo[$type]['label'] }}
            <span class="text-xs opacity-70">({{ $count }})</span>
        </a>
        @endif
        @endforeach
    </div>
</div>

{{-- Aviso de orientación para sinagogas/comunidades --}}
@if(in_array($placeType, \App\Models\KosherPlace::ORIENTABLE_TYPES))
<div class="mb-4 text-sm text-gray-500">
    @if(($orientation ?? null) === 'all')
    Mostrando comunidades de todas las orientaciones.
    <a href="{{ request()->fullUrlWithQuery(['orientation' => null]) }}" class="text-blue-600 hover:underline">Ver solo ortodoxas</a>
    @else
    Mostrando comunidades de orientación ortodoxa.
    <a href="{{ request()->fullUrlWithQuery(['orientation' => 'all']) }}" class="text-blue-600 hover:underline">Ver también conservadoras / reformistas</a>
    @endif
</div>
@endif

{{-- Filtros activos --}}
@if($query || $countrySlug || $placeType)
<div class="flex gap-2 mb-4 flex-wrap">
    @if($query)
    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold flex items-center gap-2">
        🔍 "{{ $query }}"
        <a href="{{ request()->fullUrlWithQuery(['query' => null]) }}" class="text-blue-500 hover:text-blue-700">✕</a>
    </span>
    @endif
    @if($countrySlug)
    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold flex items-center gap-2">
        🌍 {{ $countries->firstWhere('slug', $countrySlug)?->name ?? $countrySlug }}
        <a href="{{ request()->fullUrlWithQuery(['country' => null]) }}" class="text-green-500 hover:text-green-700">✕</a>
    </span>
    @endif
</div>
@endif

<div class="flex flex-col lg:flex-row gap-6">
<div class="flex-1 min-w-0">

{{-- Grid de resultados --}}
@if($places->isEmpty())
<div class="text-center py-20 text-gray-400">
    <p class="text-5xl mb-4">📍</p>
    <p class="text-lg font-medium">No encontramos locales con esos filtros.</p>
    <a href="{{ route('places.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
        Limpiar filtros
    </a>
</div>
@else

<p class="text-sm text-gray-500 mb-4">
    {{ number_format($places->total()) }} lugar{{ $places->total() !== 1 ? 'es' : '' }} encontrado{{ $places->total() !== 1 ? 's' : '' }}
</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($places as $place)
    @php
    $typeInfo  = \App\Models\KosherPlace::types()[$place->place_type] ?? \App\Models\KosherPlace::types()['other'];
    $typeBadge = [$typeInfo['badge'], $typeInfo['emoji']];
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col gap-2 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">

        <div class="flex justify-between items-start gap-2">
            <h3 class="font-bold text-gray-800 text-base leading-tight">{{ $place->name }}</h3>
            <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $typeBadge[0] }}">
                {{ $typeBadge[1] }}
            </span>
        </div>

        @if(in_array($place->place_type, \App\Models\KosherPlace::ORIENTABLE_TYPES) && $place->orientation !== 'orthodox')
        <span class="self-start text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
            {{ \App\Models\KosherPlace::orientations()[$place->orientation] ?? $place->orientation }}
        </span>
        @endif

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
            <div class="font-medium text-gray-600">
                {{ $place->city->name }}
                @if($place->city->country)
                <span class="text-gray-400">· {{ $place->city->country->name }}</span>
                @endif
            </div>
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
                🌐 Sitio web
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

<div class="mt-8">{{ $places->links() }}</div>

@endif

</div>

@if($relatedArticles->isNotEmpty())
<aside class="hidden lg:block lg:w-[26rem] shrink-0">
    <div class="sticky top-20">
        @include('partials.related_articles_sidebar')
    </div>
</aside>
@endif
</div>

<div class="lg:hidden mt-6">
    @include('partials.related_articles_sidebar')
</div>

<div class="max-w-3xl mt-16">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Preguntas frecuentes sobre lugares kosher</h2>
    <div class="space-y-5 text-sm text-gray-600 leading-relaxed">
        <div>
            <h3 class="font-semibold text-gray-800 mb-1">¿Cómo saben que un restaurante o local es realmente kosher?</h3>
            <p>La certificación kosher de un local la otorga una agencia rabínica reconocida (OU, Ajdut Kosher, etc.), no KosherMap. Nosotros relevamos el local vía Google Maps y nuestra comunidad de moderadores revisa la información antes de publicarla, pero la kashrut en sí depende de la supervisión vigente del establecimiento. Ante la duda sobre un local puntual, lo más seguro es confirmar directamente con el lugar o con la certificadora que dice tener.</p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-800 mb-1">¿Por qué por defecto solo aparecen sinagogas y comunidades ortodoxas?</h3>
            <p>Es el filtro que mejor refleja lo que busca la mayoría de nuestros usuarios, pero no es el único contenido disponible. Si tocás "Ver también conservadoras / reformistas" en la parte de arriba, el listado se amplía para incluir todas las orientaciones. Podés cambiar este filtro las veces que quieras, no queda guardado de forma permanente.</p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-800 mb-1">Encontré un local cerrado o con datos desactualizados, ¿qué hago?</h3>
            <p>Usá el botón "⚑ Reportar problema" que aparece en la tarjeta de cada lugar. Elegís el motivo (cerrado, dirección incorrecta, ya no es kosher, etc.) y opcionalmente dejás un comentario y tu email. El equipo de moderación revisa el reporte y actualiza o da de baja el local si corresponde. Si en cambio querés sumar un local que todavía no está en el directorio, usá el botón "➕ Agregar mi local".</p>
        </div>
    </div>
</div>

@endsection
