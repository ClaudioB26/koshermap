<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderación de Certificadoras — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-6xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🏅 Moderación de Certificadoras</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500">{{ auth()->user()->email }}</span>
            <a href="{{ route('admin.places.index') }}" class="text-sm text-blue-600 hover:underline">🏠 Lugares</a>
            <a href="/" class="text-sm text-blue-600 hover:underline">← Sitio</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Salir</button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if($pendingTransfers->isNotEmpty())
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
        <h2 class="font-semibold text-amber-900 mb-3">💰 Comprobantes de transferencia pendientes ({{ $pendingTransfers->count() }})</h2>
        <div class="space-y-2">
            @foreach($pendingTransfers as $payment)
            <div class="bg-white rounded-lg border border-amber-100 p-3 flex items-center justify-between flex-wrap gap-2">
                <div class="text-sm">
                    <strong>{{ $payment->certifier->name }}</strong> — Plan {{ ucfirst($payment->tier) }}
                    (${{ number_format($payment->amount, 0, ',', '.') }} {{ $payment->currency }})
                    <a href="{{ Storage::disk('public')->url($payment->transfer_proof_path) }}" target="_blank" class="text-blue-600 hover:underline ml-2">📄 Ver comprobante</a>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.certifier-transfers.approve', $payment) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">✓ Aprobar</button>
                    </form>
                    <form method="POST" action="{{ route('admin.certifier-transfers.reject', $payment) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">✗ Rechazar</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tabs de estado --}}
    <div class="flex gap-1 mb-6">
        @php
        $tabs = [
            'pending'  => '⏳ Pendientes',
            'approved' => '✅ Aprobadas',
            'rejected' => '❌ Rechazadas',
        ];
        @endphp
        @foreach($tabs as $tabStatus => $label)
        <a href="{{ route('admin.certifiers.index', ['status' => $tabStatus]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition
                  {{ $status === $tabStatus
                     ? 'bg-blue-600 text-white shadow'
                     : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
            {{ $label }}
            @if(isset($counts[$tabStatus]))
            <span class="ml-1 {{ $status === $tabStatus ? 'text-blue-200' : 'text-gray-400' }}">
                ({{ $counts[$tabStatus] }})
            </span>
            @endif
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($certifiers->isEmpty())
        <div class="p-12 text-center text-gray-400">
            No hay certificadoras con estado "{{ $status }}".
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Certificadora</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Rabino</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Cobertura</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Solicitante</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Plan / Leads</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($certifiers as $certifier)
                <tr class="hover:bg-gray-50 transition align-top">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $certifier->name }}</div>
                        @if($certifier->founded_year)
                        <div class="text-xs text-gray-400">Desde {{ $certifier->founded_year }}</div>
                        @endif
                        @if($certifier->website)
                        <a href="{{ $certifier->website }}" target="_blank" class="text-xs text-blue-500 hover:underline">🌐 {{ $certifier->website }}</a>
                        @endif
                        @if($certifier->reference_info)
                        <div class="text-xs text-gray-500 mt-1 max-w-xs">📎 {{ $certifier->reference_info }}</div>
                        @endif
                        @if(!empty($certifier->documents))
                        <div class="text-xs mt-1 space-y-0.5">
                            @foreach($certifier->documents as $doc)
                            <div><a href="{{ Storage::disk('public')->url($doc['path']) }}" target="_blank" class="text-blue-500 hover:underline">📄 {{ $doc['name'] }}</a></div>
                            @endforeach
                        </div>
                        @endif
                        @if($certifier->rejection_reason)
                        <div class="text-xs text-red-500 mt-0.5">Motivo: {{ $certifier->rejection_reason }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $certifier->rabbi_name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs">{{ $certifier->coverage_description }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $certifier->submitted_by_name }}<br>
                        <span class="text-xs text-gray-400">{{ $certifier->submitted_by_email }}</span>
                        @if($certifier->submitted_by_phone)
                        <br><span class="text-xs text-gray-400">{{ $certifier->submitted_by_phone }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($certifier->status === 'approved')
                        <form method="POST" action="{{ route('admin.certifiers.tier', $certifier) }}" class="mb-1.5">
                            @csrf
                            <select name="tier" onchange="this.form.submit()"
                                    class="text-xs border border-gray-300 rounded-lg px-2 py-1 bg-white">
                                <option value="free" @selected($certifier->tier === 'free')>Gratis</option>
                                <option value="destacada" @selected($certifier->tier === 'destacada')>Destacada</option>
                                <option value="pro" @selected($certifier->tier === 'pro')>⭐ Pro</option>
                            </select>
                        </form>
                        @endif
                        <div class="text-xs text-gray-500">{{ $certifier->leads_count }} {{ $certifier->leads_count === 1 ? 'lead' : 'leads' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 justify-end items-center flex-wrap">
                            @if($certifier->status !== 'approved')
                            <form method="POST" action="{{ route('admin.certifiers.approve', $certifier) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">
                                    ✓ Aprobar
                                </button>
                            </form>
                            @endif

                            @if($certifier->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.certifiers.reject', $certifier) }}"
                                  onsubmit="return confirmReject(this)">
                                @csrf
                                <input type="hidden" name="reason" class="reject-reason-input">
                                <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">
                                    ✗ Rechazar
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="mt-6">
        {{ $certifiers->links() }}
    </div>
</div>

<script>
function confirmReject(form) {
    const reason = prompt('Motivo del rechazo (opcional):');
    if (reason === null) return false;
    form.querySelector('.reject-reason-input').value = reason;
    return true;
}
</script>

</body>
</html>
