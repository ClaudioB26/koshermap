@extends('layouts.app')

@section('title', $product->name . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

{{-- Botón volver (arriba) --}}
<div class="mb-6">
    <button onclick="history.back()"
            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-blue-600
                   bg-white border border-gray-200 hover:border-blue-400 px-4 py-2 rounded-lg shadow-sm transition">
        ← {{ __('go_back') }}
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header del producto --}}
    <div class="p-6 md:p-8">
        <div class="flex flex-col md:flex-row gap-8">

            {{-- Imagen --}}
            <div class="flex-shrink-0 flex items-center justify-center w-full md:w-48">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         class="max-h-48 w-auto object-contain rounded-xl">
                @else
                    <div class="w-32 h-32 bg-gray-100 rounded-xl flex items-center justify-center text-4xl">
                        📦
                    </div>
                @endif
            </div>

            {{-- Info principal --}}
            <div class="flex-grow">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                    {{ $product->name }}
                </h1>

                <p class="text-blue-600 font-semibold text-lg mb-4">
                    {{ $product->brand->name ?? __('Generic Brand') }}
                </p>

                <div class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold text-sm mb-6">
                    ✓ {{ __('Kosher Status') }}: {{ strtoupper($product->kosher_status) }}
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <dt class="text-gray-500">{{ __('certifier') }}</dt>
                        <dd class="font-semibold text-gray-800">
                            @if($product->certifier)
                                <a href="{{ route('certifiers.show', $product->certifier->slug) }}"
                                   class="text-blue-600 hover:underline">
                                    {{ $product->certifier->name }}
                                </a>
                            @else
                                {{ __('N/A') }}
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <dt class="text-gray-500">{{ __('barcode') }}</dt>
                        <dd class="font-mono font-semibold text-gray-800">{{ $product->barcode ?? __('N/A') }}</dd>
                    </div>
                    @if($product->category)
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <dt class="text-gray-500">{{ __('categories') }}</dt>
                        <dd class="font-semibold text-gray-800">{{ $product->category->name }}</dd>
                    </div>
                    @endif
                </dl>

                @if($product->description)
                <p class="mt-4 text-gray-600 text-sm leading-relaxed">{{ $product->description }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Sección de comentarios y valoraciones --}}
    <div class="border-t border-gray-100 p-6 md:p-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">{{ __('reviews_and_ratings') }}</h2>

        {{-- Mensaje tras enviar comentario --}}
        @if(session('review_sent'))
        <div class="mb-5 px-4 py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl text-sm font-medium flex items-start gap-2">
            <span class="text-lg leading-none">✉️</span>
            <span>Tu comentario fue recibido y será revisado por el moderador antes de publicarse. ¡Gracias!</span>
        </div>
        @endif

        {{-- Formulario para agregar comentario --}}
        <div class="bg-gray-50 rounded-xl p-5 mb-8 border border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">
                ✏️ {{ __('Post Comment') }}
            </h3>
            <form method="POST" action="{{ route('reviews.store', $product->slug) }}" class="space-y-4">
                @csrf

                {{-- Nombre --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        {{ __('Your Name (Optional)') }}
                    </label>
                    <input type="text" name="author_name"
                           value="{{ old('author_name') }}"
                           placeholder="{{ __('Anonymous') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                </div>

                {{-- Estrellas --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2">
                        {{ __('Rating') }}
                    </label>
                    <div class="flex gap-2" x-data="{ rating: {{ old('rating', 0) }} }">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}"
                                   class="sr-only"
                                   x-on:change="rating = {{ $i }}"
                                   {{ old('rating') == $i ? 'checked' : '' }}>
                            <span class="text-2xl transition"
                                  :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'">★</span>
                        </label>
                        @endfor
                        <span class="text-xs text-gray-400 self-center ml-2" x-text="[
                            '', '{{ __('Terrible') }}', '{{ __('Bad') }}', '{{ __('Regular') }}', '{{ __('Good') }}', '{{ __('Excellent') }}'
                        ][rating]"></span>
                    </div>
                    @error('rating')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comentario --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        {{ __('Comment') }}
                    </label>
                    <textarea name="content" rows="3"
                              placeholder="{{ __('Write your opinion...') }}"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                                     focus:ring-2 focus:ring-blue-500 outline-none bg-white resize-none">{{ old('content') }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
                               px-5 py-2 rounded-lg transition">
                    {{ __('Post Comment') }}
                </button>
            </form>
        </div>

        {{-- Lista de comentarios visibles (aprobados + pasaron 5 min) --}}
        @php $visibleReviews = $product->reviews()->visible()->latest('approved_at')->get(); @endphp
        @if($visibleReviews->count() > 0)
        <div class="space-y-4">
            @foreach($visibleReviews as $review)
            <div class="border-b border-gray-100 pb-4 last:border-b-0">
                <div class="flex justify-between items-start mb-1">
                    <span class="font-semibold text-gray-800 text-sm">{{ $review->author_name }}</span>
                    <span class="text-yellow-400 text-sm">
                        {{ str_repeat('★', $review->rating) }}<span class="text-gray-200">{{ str_repeat('★', 5 - $review->rating) }}</span>
                    </span>
                </div>
                <p class="text-gray-600 text-sm">{{ $review->content }}</p>
                <p class="text-gray-400 text-xs mt-1">{{ $review->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
        @else
            <p class="text-gray-400 italic text-sm">{{ __('no_reviews_yet') }}</p>
            <p class="text-gray-300 text-xs mt-1">Los comentarios aparecen luego de ser revisados por el moderador.</p>
        @endif
    </div>
</div>

{{-- Reportar problema --}}
<div class="mt-6">
    @include('partials.report_form', [
        'route'   => route('products.report', $product),
        'reasons' => \App\Models\Report::reasonsProduct(),
        'label'   => $product->name,
    ])
</div>

{{-- Botón volver (abajo) --}}
<div class="mt-6">
    <button onclick="history.back()"
            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-blue-600
                   bg-white border border-gray-200 hover:border-blue-400 px-4 py-2 rounded-lg shadow-sm transition">
        ← {{ __('go_back') }}
    </button>
</div>

@endsection
