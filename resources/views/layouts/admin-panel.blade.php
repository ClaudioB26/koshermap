<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — KosherMap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen md:flex">

@php
// Menu del panel. 'badge' = cantidad de cosas que esperan una accion tuya
// (calculada en AppServiceProvider para no repetir consultas en cada vista).
$menu = [
    ['route' => 'admin.certifiers.index', 'match' => 'admin.certifiers.*', 'icon' => '🏅', 'label' => 'Certificadoras', 'badge' => $adminBadges['certifiers'] ?? 0, 'badgeClass' => 'bg-red-500'],
    ['route' => 'admin.places.index',     'match' => 'admin.places.*',     'icon' => '🏠', 'label' => 'Lugares',        'badge' => $adminBadges['places'] ?? 0,     'badgeClass' => 'bg-red-500'],
    ['route' => 'admin.leads.index',      'match' => 'admin.leads.*',      'icon' => '📥', 'label' => 'Leads',          'badge' => $adminBadges['leads'] ?? 0,      'badgeClass' => 'bg-blue-500', 'badgeTitle' => 'últimos 7 días'],
    ['route' => 'admin.reports.index',    'match' => 'admin.reports.*',    'icon' => '🚩', 'label' => 'Reportes',       'badge' => $adminBadges['reports'] ?? 0,    'badgeClass' => 'bg-red-500'],
    ['route' => 'admin.reviews.index',    'match' => 'admin.reviews.*',    'icon' => '💬', 'label' => 'Comentarios',    'badge' => $adminBadges['reviews'] ?? 0,    'badgeClass' => 'bg-red-500'],
    ['route' => 'admin.banners.index',    'match' => 'admin.banners.*',    'icon' => '📢', 'label' => 'Banners',        'badge' => $adminBadges['banners'] ?? 0,    'badgeClass' => 'bg-amber-500', 'badgeTitle' => 'vencen en los próximos 7 días'],
];
@endphp

<aside class="bg-white border-b border-gray-200 md:border-b-0 md:border-r md:w-56 md:shrink-0 md:h-screen md:sticky md:top-0 md:flex md:flex-col">
    <div class="px-5 py-4 flex items-center justify-between md:block">
        <a href="{{ route('admin.certifiers.index') }}" class="text-xl font-black text-blue-600 leading-none">
            Kosher<span class="text-gray-800">Map</span>
            <span class="block text-[10px] font-semibold uppercase tracking-widest text-gray-400 mt-1">Administración</span>
        </a>
        <span class="text-xs text-gray-400 md:hidden">{{ auth()->user()->email }}</span>
    </div>

    <nav class="flex md:flex-col gap-1 px-3 pb-3 md:pb-0 overflow-x-auto md:overflow-visible">
        @foreach($menu as $item)
        @php $active = request()->routeIs($item['match']); @endphp
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-2.5 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition
                  {{ $active ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="text-base leading-none">{{ $item['icon'] }}</span>
            <span>{{ $item['label'] }}</span>
            @if($item['badge'] > 0)
            <span class="ml-auto text-[11px] font-bold text-white rounded-full px-1.5 min-w-[1.25rem] text-center {{ $item['badgeClass'] }}"
                  @isset($item['badgeTitle']) title="{{ $item['badgeTitle'] }}" @endisset>{{ $item['badge'] }}</span>
            @endif
        </a>
        @endforeach

        {{-- En celular, "Ver sitio" y "Salir" van en la misma barra --}}
        <a href="/" class="md:hidden flex items-center gap-2.5 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium text-gray-600">← Sitio</a>
        <form method="POST" action="{{ route('admin.logout') }}" class="md:hidden">
            @csrf
            <button class="flex items-center gap-2.5 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium text-red-500">Salir</button>
        </form>
    </nav>

    <div class="hidden md:block mt-auto border-t border-gray-100 p-4 space-y-2">
        <div class="text-xs text-gray-400 truncate" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</div>
        <a href="/" class="block text-sm text-blue-600 hover:underline">← Ver el sitio</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="text-sm text-red-500 hover:text-red-700">Cerrar sesión</button>
        </form>
    </div>
</aside>

<main class="flex-1 min-w-0 px-4 py-6 md:px-8 md:py-8">
    <div class="max-w-6xl mx-auto">
        @yield('content')
    </div>
</main>

</body>
</html>
