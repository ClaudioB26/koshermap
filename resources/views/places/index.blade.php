@extends('layouts.app')

@section('title', __('places.meta_title'))
@section('meta_description', __('places.meta_description'))

@section('content')

<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">📍 {{ __('places.h1') }}</h1>
        <p class="text-gray-500 text-sm mt-0.5">{{ __('places.subtitle') }}</p>
    </div>
    <a href="{{ route('places.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shrink-0">
        ➕ {{ __('places.add') }}
    </a>
</div>

<div class="max-w-3xl mb-8 text-gray-600 text-sm leading-relaxed">
    <p class="mb-3">{{ __('places.intro_1') }}</p>
    <p>{{ __('places.intro_2') }}</p>
</div>

{{-- Filtros --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 items-center">

    {{-- Filtro país --}}
    <form method="GET" action="{{ route('places.index') }}" class="flex items-center gap-2">
        @if($query) <input type="hidden" name="query" value="{{ $query }}"> @endif
        @if($placeType) <input type="hidden" name="place_type" value="{{ $placeType }}"> @endif
        <select name="country" onchange="this.form.submit()"
                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">🌍 {{ __('places.all_countries') }}</option>
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
            {{ __('places.all_types') }}
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
    {{ __('places.orient_all') }}
    <a href="{{ request()->fullUrlWithQuery(['orientation' => null]) }}" class="text-blue-600 hover:underline">{{ __('places.orient_only_orthodox') }}</a>
    @else
    {{ __('places.orient_orthodox') }}
    <a href="{{ request()->fullUrlWithQuery(['orientation' => 'all']) }}" class="text-blue-600 hover:underline">{{ __('places.orient_see_more') }}</a>
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
    <p class="text-lg font-medium">{{ __('places.empty') }}</p>
    <a href="{{ route('places.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">
        {{ __('places.clear') }}
    </a>
</div>
@else

<p class="text-sm text-gray-500 mb-4">
    {{ trans_choice('places.found', $places->total(), ['count' => number_format($places->total())]) }}
</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($places as $place)
    @php
    $typeInfo  = \App\Models\KosherPlace::types()[$place->place_type] ?? \App\Models\KosherPlace::types()['other'];
    $typeBadge = [$typeInfo['badge'], $typeInfo['emoji']];
    // Mismo criterio que /certifiers: recuadro segun plan en vez de un
    // texto tipo "Premium"/"Destacado".
    $cardClass = match($place->tier ?? 'free') {
        'premium' => 'bg-amber-50/60 rounded-2xl shadow-md border-2 border-amber-300 p-5 flex flex-col gap-2 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200',
        'destacada_rubro' => 'bg-white rounded-2xl shadow-sm border-2 border-blue-300 p-5 flex flex-col gap-2 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200',
        default => 'bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col gap-2 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200',
    };
    $nameClass = ($place->tier ?? 'free') === 'premium' ? 'font-extrabold text-amber-900 text-base leading-tight' : 'font-bold text-gray-800 text-base leading-tight';
    @endphp
    <div class="{{ $cardClass }}">

        <div class="flex justify-between items-start gap-2">
            <h3 class="{{ $nameClass }}">{{ $place->name }}</h3>
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
                🌐 {{ __('places.website') }}
            </a>
            @endif
        </div>

        <div x-data="{ open: false }" class="pt-1 border-t border-gray-100 mt-1">
            <button @click="open = !open" type="button"
                    class="text-xs text-gray-400 hover:text-red-500 transition flex items-center gap-1">
                ⚑ {{ __('places.report') }}
            </button>
            <div x-show="open" x-transition class="mt-2">
                <form method="POST" action="{{ route('places.report', $place) }}" class="space-y-2">
                    @csrf
                    <select name="reason" required
                            class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white">
                        <option value="">— {{ __('places.reason') }} —</option>
                        @foreach(\App\Models\Report::reasonsPlace() as $val => $lbl)
                        <option value="{{ $val }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <textarea name="observation" rows="2" placeholder="{{ __('places.observation') }}"
                              class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white resize-none"></textarea>
                    <input type="email" name="email" placeholder="{{ __('places.your_email') }}"
                           class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white">
                    @include('partials.turnstile')
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">
                            {{ __('places.send') }}
                        </button>
                        <button type="button" @click="open = false"
                                class="text-xs text-gray-400 hover:text-gray-600">{{ __('places.cancel') }}</button>
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
    <h2 class="text-xl font-bold text-gray-800 mb-6">{{ __('places.faq_title') }}</h2>
    <div class="space-y-5 text-sm text-gray-600 leading-relaxed">
        <div>
            <h3 class="font-semibold text-gray-800 mb-1">{{ __('places.faq1_q') }}</h3>
            <p>{{ __('places.faq1_a') }}</p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-800 mb-1">{{ __('places.faq2_q') }}</h3>
            <p>{{ __('places.faq2_a') }}</p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-800 mb-1">{{ __('places.faq3_q') }}</h3>
            <p>{{ __('places.faq3_a') }}</p>
        </div>
    </div>
</div>

@endsection
