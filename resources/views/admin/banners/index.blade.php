@extends('layouts.admin-panel')
@section('title', 'Banners')
@section('content')
<div>
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h1 class="text-2xl font-bold text-gray-800">📢 Banners publicitarios</h1>
        <a href="{{ route('admin.banners.create') }}"
           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            ➕ Nuevo banner
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Totales --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        @foreach([
            ['Activos ahora', $totals['active'], null],
            ['Impresiones', number_format($totals['impressions']), 'veces que se mostraron'],
            ['Clics', number_format($totals['clicks']), 'visitas enviadas a anunciantes'],
            ['CTR', $totals['ctr'] !== null ? $totals['ctr'] . '%' : '—', 'clics / impresiones'],
        ] as [$label, $value, $hint])
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3">
            <div class="text-xs text-gray-400">{{ $label }}</div>
            <div class="text-2xl font-bold text-gray-800">{{ $value }}</div>
            @if($hint)<div class="text-[11px] text-gray-400">{{ $hint }}</div>@endif
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($banners->isEmpty())
        <div class="p-12 text-center text-gray-400">
            Todavía no cargaste ningún banner.<br>
            <a href="{{ route('admin.banners.create') }}" class="text-blue-600 hover:underline">Crear el primero</a>
        </div>
        @else
        @php
        $statusInfo = [
            'active'    => ['Activo',     'bg-green-100 text-green-700'],
            'paused'    => ['Pausado',    'bg-gray-100 text-gray-600'],
            'scheduled' => ['Programado', 'bg-blue-100 text-blue-700'],
            'expired'   => ['Vencido',    'bg-red-100 text-red-700'],
        ];
        @endphp
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Banner</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Dónde</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Vigencia</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Estado</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Impr.</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Clics</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">CTR</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($banners as $b)
                @php [$stLabel, $stClass] = $statusInfo[$b->status]; @endphp
                <tr class="align-top hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-start gap-3">
                            <img src="{{ route('banners.image', $b) }}" alt="" class="w-28 h-auto rounded border border-gray-200 shrink-0">
                            <div class="min-w-0">
                                <div class="font-medium text-gray-800">{{ $b->name }}</div>
                                @if($b->advertiser)<div class="text-xs text-gray-500">{{ $b->advertiser }}</div>@endif
                                <a href="{{ $b->target_url }}" target="_blank" rel="noopener" class="text-xs text-blue-500 hover:underline break-all">{{ \Illuminate\Support\Str::limit($b->target_url, 40) }}</a>
                                @if($b->notes)<div class="text-xs text-gray-400 mt-1 max-w-xs" title="{{ $b->notes }}">📝 {{ \Illuminate\Support\Str::limit($b->notes, 60) }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        {{ \App\Models\Banner::SLOTS[$b->slot] ?? $b->slot }}<br>
                        <span class="text-gray-400">{{ \App\Models\Banner::PAGES[$b->pages] ?? $b->pages }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                        {{ $b->starts_on?->format('d/m/Y') ?? 'sin inicio' }}<br>
                        → {{ $b->ends_on?->format('d/m/Y') ?? 'sin fin' }}
                    </td>
                    <td class="px-4 py-3"><span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $stClass }}">{{ $stLabel }}</span></td>
                    <td class="px-4 py-3 text-right text-gray-700">{{ number_format($b->impressions) }}</td>
                    <td class="px-4 py-3 text-right text-gray-700">{{ number_format($b->clicks) }}</td>
                    <td class="px-4 py-3 text-right text-gray-700">{{ $b->ctr !== null ? $b->ctr . '%' : '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 justify-end items-center flex-wrap">
                            <a href="{{ route('admin.banners.edit', $b) }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 text-xs rounded-lg hover:bg-gray-50 transition">Editar</a>
                            <form method="POST" action="{{ route('admin.banners.toggle', $b) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-xs rounded-lg transition {{ $b->is_active ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-green-600 text-white hover:bg-green-700' }}">
                                    {{ $b->is_active ? 'Pausar' : 'Activar' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.banners.destroy', $b) }}" onsubmit="return confirm('¿Eliminar el banner &quot;{{ addslashes($b->name) }}&quot; y su imagen? Se pierden sus estadísticas.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>

    <p class="text-xs text-gray-400 mt-4">
        Los banners aparecen solo en artículos y en la página de certificadoras, con la etiqueta "Publicidad".
        Las impresiones y clics no cuentan bots ni tus propias visitas como admin.
    </p>
</div>
@endsection
