@extends('layouts.admin-panel')
@section('title', 'Leads')
@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📥 Leads de certificación</h1>

    {{-- Resumen por certificadora: el numero para mostrarle a cada una --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <h2 class="font-semibold text-gray-700 mb-3">Resumen por certificadora</h2>
        @if($summary->isEmpty())
            <p class="text-sm text-gray-400">Todavía no llegó ningún lead.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($summary as $cert)
            <a href="{{ route('admin.leads.index', ['certifier' => $cert->id]) }}"
               class="block rounded-lg border px-4 py-3 transition hover:border-blue-400
                      {{ $certifierId === $cert->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white' }}">
                <div class="font-medium text-gray-800">{{ $cert->name }}</div>
                <div class="text-sm text-gray-500 mt-0.5">
                    <strong class="text-gray-800">{{ $cert->leads_count }}</strong> en total ·
                    <strong class="text-gray-800">{{ $cert->leads_30d_count }}</strong> en los últimos 30 días
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
        <p class="text-sm text-gray-500">
            {{ number_format($leads->total()) }} lead{{ $leads->total() === 1 ? '' : 's' }}
            @if($certifierId)
                de esta certificadora ·
                <a href="{{ route('admin.leads.index') }}" class="text-blue-600 hover:underline">ver todos ({{ $total }})</a>
            @endif
        </p>
        @if($leads->total() > 0)
        <a href="{{ route('admin.leads.index', array_filter(['certifier' => $certifierId, 'export' => 'csv'])) }}"
           class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition">
            ⬇️ Exportar CSV
        </a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($leads->isEmpty())
        <div class="p-12 text-center text-gray-400">No hay leads para mostrar.</div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Fecha</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Certificadora</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Empresa</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Contacto</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Producto / mensaje</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($leads as $lead)
                <tr class="align-top hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                        {{ $lead->created_at->format('d/m/Y') }}<br>
                        <span class="text-xs text-gray-400">{{ $lead->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $lead->certifier?->name ?? '—' }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $lead->company }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $lead->name }}<br>
                        <a href="mailto:{{ $lead->email }}" class="text-xs text-blue-600 hover:underline">{{ $lead->email }}</a>
                        @if($lead->phone)
                        <br><span class="text-xs text-gray-400">{{ $lead->phone }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-sm">
                        @if($lead->product_type)
                        <div class="font-medium text-gray-700">{{ $lead->product_type }}</div>
                        @endif
                        @if($lead->message)
                        <div class="text-gray-500 text-xs whitespace-pre-line break-words">{{ $lead->message }}</div>
                        @elseif(!$lead->product_type)
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="mt-6">
        {{ $leads->links() }}
    </div>
</div>

@endsection
