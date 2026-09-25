@extends('layouts.admin-panel')
@section('title', 'Reportes')
@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">🚩 Reportes de usuarios</h1>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6">
        @foreach(['pending' => '⏳ Pendientes', 'reviewed' => '✅ Revisados'] as $tab => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $tab, 'page' => null]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition
                  {{ $status === $tab ? 'bg-blue-600 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            {{ $label }}
            @if(isset($counts[$tab]))
            <span class="ml-1 {{ $status === $tab ? 'text-blue-200' : 'text-gray-400' }}">({{ $counts[$tab] }})</span>
            @endif
        </a>
        @endforeach
    </div>

    <div class="space-y-4">
        @forelse($reports as $report)
        @php
            $item      = $report->reportable;
            $isProduct = $item instanceof \App\Models\Product;
            $isPlace   = $item instanceof \App\Models\KosherPlace;
            $typeLabel = $isProduct ? '📦 Producto' : '📍 Lugar';
            $typeColor = $isProduct ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700';
        @endphp
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex justify-between items-start gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                    {{-- Tipo + nombre del item --}}
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $typeColor }}">{{ $typeLabel }}</span>
                        @if($item)
                        <span class="font-semibold text-gray-800 truncate">{{ $item->name }}</span>
                        @else
                        <span class="text-gray-400 italic text-sm">Item eliminado</span>
                        @endif

                        @if($isPlace && $item)
                        <span class="text-xs text-gray-400">— {{ $item->city->name ?? '' }}</span>
                        @endif
                    </div>

                    {{-- Motivo --}}
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm mt-2">
                        <span class="font-medium text-red-600">
                            Motivo: {{ $report->reasonLabel() }}
                        </span>
                        @if($report->email)
                        <span class="text-gray-500">📧 {{ $report->email }}</span>
                        @endif
                        <span class="text-gray-400 text-xs">{{ $report->created_at->diffForHumans() }}</span>
                    </div>

                    @if($report->observation)
                    <div class="mt-2 p-3 bg-gray-50 rounded-lg text-sm text-gray-700 border border-gray-100">
                        "{{ $report->observation }}"
                    </div>
                    @endif

                    @if($report->admin_notes)
                    <div class="mt-2 text-xs text-gray-500 italic">Nota admin: {{ $report->admin_notes }}</div>
                    @endif
                </div>

                {{-- Acciones --}}
                <div class="flex flex-col gap-2 shrink-0">
                    @if($isProduct && $item)
                    <a href="{{ route('products.show', $item->slug) }}" target="_blank"
                       class="text-xs text-blue-500 hover:underline text-center">Ver producto ↗</a>
                    @endif
                    @if($isPlace && $item)
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($item->name . ' ' . $item->address) }}"
                       target="_blank" class="text-xs text-blue-500 hover:underline text-center">Ver en Maps ↗</a>
                    @endif

                    @if($report->isPending())
                    <form method="POST" action="{{ route('admin.reports.review', $report) }}">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Nota interna (opcional)"
                               class="w-full text-xs border border-gray-300 rounded-lg px-2 py-1 mb-1">
                        <button type="submit"
                                class="w-full px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">
                            ✓ Marcar revisado
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-gray-400 text-center">
                        Revisado {{ $report->reviewed_at?->diffForHumans() }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-400 bg-white rounded-xl border border-gray-200">
            No hay reportes {{ $status === 'pending' ? 'pendientes' : 'revisados' }}.
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reports->links() }}
    </div>
</div>

@endsection
