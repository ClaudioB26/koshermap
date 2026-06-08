<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['he', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KosherStatus')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    @if(app()->environment('production'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'G-XXXXXXXXXX');
        </script>
    @endif
    @stack('head')
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center gap-3">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-xl font-black text-blue-600 shrink-0 hover:opacity-80 transition">
                Kosher<span class="text-gray-800">Status</span>
            </a>

            <!-- Primary Nav (desktop) -->
            <nav class="hidden md:flex items-center gap-1 shrink-0">
                <a href="{{ route('home') }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition
                          {{ request()->routeIs('home') || request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('certifiers.*') || request()->routeIs('brands.*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    🛒 Productos
                </a>
                <a href="{{ route('places.index') }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition
                          {{ request()->routeIs('places.*') || request()->routeIs('countries.*') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    📍 Lugares
                </a>
                <a href="{{ route('certifiers.index') }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium transition text-gray-500 hover:bg-gray-100">
                    🏅 Certif.
                </a>
            </nav>

            <!-- Search Bar -->
            <div class="flex-grow">
                <form action="{{ request()->routeIs('places.*') || request()->routeIs('countries.*') ? route('places.index') : route('home') }}"
                      method="GET" class="relative">
                    @if(isset($country) && $country instanceof \App\Models\Country)
                        <input type="hidden" name="country" value="{{ $country->slug }}">
                    @elseif(isset($userCountry) && $userCountry instanceof \App\Models\Country && !request()->has('country'))
                        <input type="hidden" name="country" value="{{ $userCountry->slug }}">
                    @endif
                    @if(isset($category) && $category instanceof \App\Models\Category)
                        <input type="hidden" name="category" value="{{ $category->slug }}">
                    @endif
                    @if(isset($certifier) && $certifier instanceof \App\Models\Certifier)
                        <input type="hidden" name="certifier" value="{{ $certifier->slug }}">
                    @endif

                    <input type="text" id="query-input" name="query"
                           value="{{ request('query') }}"
                           placeholder="{{ request()->routeIs('places.*') || request()->routeIs('countries.*') ? 'Buscar local kosher...' : __('search_placeholder') . '...' }}"
                           class="w-full pl-4 pr-20 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm text-sm">

                    <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <button type="button" id="scan-btn"
                                class="text-gray-400 hover:text-blue-600 p-1 transition" title="Escanear código">
                            📷
                        </button>
                        <button type="submit" class="text-gray-400 hover:text-blue-600 p-1 transition">
                            🔍
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: location + lang -->
            <div class="flex items-center gap-2 shrink-0">
                @if(isset($userCountry))
                <div class="hidden lg:block relative group">
                    <button class="text-sm font-medium text-gray-600 hover:text-blue-600 flex items-center gap-1 whitespace-nowrap">
                        📍 {{ $userCountry->name }}
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute right-0 rtl:right-auto rtl:left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                        <a href="{{ route('countries.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                            {{ __('change_country') }}
                        </a>
                    </div>
                </div>
                @endif
                <div class="hidden md:block">
                    @include('partials.language_switcher')
                </div>
            </div>
        </div>

        <!-- QR scanner (slides open below search bar) -->
        <div id="reader" class="hidden mt-3 rounded-xl overflow-hidden shadow-lg max-w-sm mx-auto"></div>

        <!-- Mobile Nav -->
        <nav class="md:hidden flex gap-2 mt-2 overflow-x-auto pb-1 -mx-1 px-1">
            <a href="{{ route('home') }}"
               class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                      {{ request()->routeIs('home') || request()->routeIs('products.*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                🛒 Productos
            </a>
            <a href="{{ route('places.index') }}"
               class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                      {{ request()->routeIs('places.*') || request()->routeIs('countries.*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                📍 Lugares
            </a>
            <a href="{{ route('certifiers.index') }}"
               class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap bg-gray-100 text-gray-600">
                🏅 Certif.
            </a>
            <a href="{{ route('countries.index') }}"
               class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap bg-gray-100 text-gray-600">
                🌍 Países
            </a>
            @include('partials.language_switcher')
        </nav>
    </div>
</header>

<main class="flex-grow container mx-auto px-4 py-8">
    @yield('content')
</main>

<footer class="bg-white border-t py-8 mt-auto">
    <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
        <p class="font-semibold text-gray-700 mb-1">Kosher<span class="text-blue-600">Status</span></p>
        <p>{{ __('footer_text') }}</p>
        <div class="flex justify-center gap-4 mt-3 text-xs text-gray-400">
            <a href="{{ route('places.index') }}" class="hover:text-blue-600">📍 Lugares kosher</a>
            <a href="{{ route('countries.index') }}" class="hover:text-blue-600">🌍 Países</a>
            <a href="{{ route('certifiers.index') }}" class="hover:text-blue-600">🏅 Certificadoras</a>
        </div>
    </div>
</footer>

<!-- QR Scanner Script -->
<script>
(function () {
    const scanBtn   = document.getElementById('scan-btn');
    const readerDiv = document.getElementById('reader');
    const queryInput = document.getElementById('query-input');
    if (!scanBtn || !readerDiv || !queryInput) return;

    const html5QrCode = new Html5Qrcode("reader");
    let isScanning = false;

    scanBtn.addEventListener('click', () => {
        if (isScanning) {
            html5QrCode.stop().then(() => {
                readerDiv.classList.add('hidden');
                isScanning = false;
            }).catch(() => {});
            return;
        }
        readerDiv.classList.remove('hidden');
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                queryInput.value = decodedText;
                html5QrCode.stop().then(() => {
                    readerDiv.classList.add('hidden');
                    isScanning = false;
                    queryInput.closest('form').submit();
                });
            },
            () => {}
        ).then(() => { isScanning = true; })
         .catch(() => {
             alert("{{ __('Camera access failed. Please ensure permissions are granted.') }}");
             readerDiv.classList.add('hidden');
         });
    });
})();
</script>
@stack('scripts')
</body>
</html>
