<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderación de Lugares — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🏠 Moderación de Lugares Kosher</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500">{{ auth()->user()->email }}</span>
            <a href="{{ route('admin.reports.index') }}" class="text-sm text-blue-600 hover:underline">⚑ Reportes
                @php $pendingReports = \App\Models\Report::where('status','pending')->count(); @endphp
                @if($pendingReports > 0)
                <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5">{{ $pendingReports }}</span>
                @endif
            </a>
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

    @php $pendingSync = \App\Models\KosherPlace::pendingSync()->count(); @endphp
    @if($pendingSync > 0)
    <div class="mb-6 flex items-center justify-between gap-4 bg-amber-50 border border-amber-300 rounded-xl px-5 py-3">
        <div class="flex items-center gap-2 text-amber-800 text-sm">
            <span class="text-lg">⬆️</span>
            <span>
                <strong>{{ $pendingSync }} lugar{{ $pendingSync > 1 ? 'es' : '' }}</strong>
                aprobado{{ $pendingSync > 1 ? 's' : '' }} sin sincronizar con producción.
            </span>
        </div>
        <div class="flex items-center gap-3 text-sm shrink-0">
            <code class="bg-amber-100 text-amber-900 px-2 py-1 rounded text-xs">php artisan sync:push</code>
            <span class="text-amber-500">o</span>
            <code class="bg-amber-100 text-amber-900 px-2 py-1 rounded text-xs">sync:push --dry-run</code>
        </div>
    </div>
    @else
    <div class="mb-6 flex items-center gap-2 text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl px-5 py-3">
        ✓ Todo sincronizado con producción.
    </div>
    @endif

    {{-- Tabs de estado --}}
    <div class="flex gap-1 mb-6">
        @php
        $tabs = [
            'pending'  => '⏳ Pendientes',
            'approved' => '✅ Aprobados',
            'rejected' => '❌ Rechazados',
        ];
        @endphp
        @foreach($tabs as $tabStatus => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $tabStatus, 'page' => null]) }}"
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

    {{-- Filtros --}}
    <form method="GET" class="flex gap-3 mb-4 flex-wrap">
        <input type="hidden" name="status" value="{{ $status }}">
        <select name="country" class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
            <option value="">Todos los países</option>
            @foreach($countries as $c)
            <option value="{{ $c->code }}" {{ $country === $c->code ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
        <select name="type" class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white">
            <option value="">Todos los tipos</option>
            @foreach(\App\Models\KosherPlace::types() as $t => $info)
            <option value="{{ $t }}" {{ $type === $t ? 'selected' : '' }}>{{ $info['emoji'] }} {{ $info['label'] }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-800">Filtrar</button>
        <a href="{{ route('admin.places.index', ['status' => $status]) }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
    </form>

    {{-- Formulario bulk (envuelve toda la tabla) --}}
    <form id="bulk-form" method="POST" action="{{ route('admin.places.bulk') }}">
        @csrf
        <input type="hidden" name="action" id="bulk-action" value="">
        <input type="hidden" name="reason" id="bulk-reason" value="">

        {{-- Barra de acciones en lote (solo visible cuando hay selección) --}}
        <div id="bulk-bar"
             class="hidden mb-3 flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
            <span id="bulk-count" class="text-sm font-semibold text-blue-700">0 seleccionados</span>
            <div class="flex gap-2 ml-auto">
                <button type="button" onclick="submitBulk('approve')"
                        class="px-4 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition font-medium">
                    ✓ Aprobar seleccionados
                </button>
                <button type="button" onclick="submitBulk('reject')"
                        class="px-4 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition font-medium">
                    ✗ Rechazar seleccionados
                </button>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($places->isEmpty())
            <div class="p-12 text-center text-gray-400">
                No hay lugares con estado "{{ $status }}"
                @if($country || $type) con los filtros seleccionados @endif.
            </div>
            @else
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        @if($status === 'pending')
                        <th class="px-4 py-3 w-8">
                            <input type="checkbox" id="check-all"
                                   class="rounded border-gray-300 text-blue-600 cursor-pointer"
                                   title="Seleccionar todos">
                        </th>
                        @endif
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Lugar</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Tipo</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Ciudad</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Rating</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Dirección</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($places as $place)
                    @php
                    $badge = (\App\Models\KosherPlace::types()[$place->place_type] ?? \App\Models\KosherPlace::types()['other'])['badge'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition row-item">
                        @if($status === 'pending')
                        <td class="px-4 py-3">
                            <input type="checkbox" name="ids[]" value="{{ $place->id }}"
                                   class="row-check rounded border-gray-300 text-blue-600 cursor-pointer">
                        </td>
                        @endif
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $place->name }}</div>
                            @if($place->rejection_reason)
                            <div class="text-xs text-red-500 mt-0.5">Motivo: {{ $place->rejection_reason }}</div>
                            @endif
                            <div class="flex gap-2 mt-1">
                                @if($place->website)
                                <a href="{{ $place->website }}" target="_blank" class="text-xs text-blue-500 hover:underline">🌐 Web</a>
                                @endif
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($place->name . ' ' . $place->address) }}"
                                   target="_blank" class="text-xs text-blue-500 hover:underline">📍 Maps</a>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.places.update-type', $place) }}">
                                @csrf
                                <select name="place_type" onchange="this.form.submit()"
                                        class="px-2 py-0.5 rounded-full text-xs font-medium border-0 cursor-pointer {{ $badge }}">
                                    @foreach(\App\Models\KosherPlace::types() as $t => $info)
                                    <option value="{{ $t }}" {{ $place->place_type === $t ? 'selected' : '' }}>
                                        {{ $info['emoji'] }} {{ $info['label'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $place->city->name }}<br>
                            <span class="text-xs text-gray-400">{{ $place->city->country->name }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($place->google_rating)
                            <span class="text-yellow-500">★</span>
                            <span class="font-medium">{{ number_format($place->google_rating, 1) }}</span>
                            <span class="text-xs text-gray-400">({{ number_format($place->google_reviews_count) }})</span>
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate">
                            {{ $place->address ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 justify-end items-center">
                                @if($place->status !== 'approved')
                                <form method="POST" action="{{ route('admin.places.approve', $place) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition">
                                        ✓ Aprobar
                                    </button>
                                </form>
                                @endif

                                @if($place->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.places.reject', $place) }}"
                                      onsubmit="return confirmReject(this)">
                                    @csrf
                                    <input type="hidden" name="reason" class="reject-reason-input">
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition">
                                        ✗ Rechazar
                                    </button>
                                </form>
                                @endif

                                @if($place->status !== 'pending')
                                <form method="POST" action="{{ route('admin.places.pending', $place) }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1 bg-gray-400 text-white text-xs rounded-lg hover:bg-gray-500 transition"
                                            title="Volver a pendiente">
                                        ↺
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
    </form>

    <div class="mt-6">
        {{ $places->links() }}
    </div>
</div>

<script>
// Seleccionar todos
const checkAll = document.getElementById('check-all');
if (checkAll) {
    checkAll.addEventListener('change', () => {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll.checked);
        updateBulkBar();
    });
}

document.querySelectorAll('.row-check').forEach(cb => {
    cb.addEventListener('change', () => {
        updateBulkBar();
        if (!cb.checked && checkAll) checkAll.checked = false;
    });
});

function updateBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked').length;
    const bar     = document.getElementById('bulk-bar');
    const count   = document.getElementById('bulk-count');
    if (!bar) return;
    bar.classList.toggle('hidden', checked === 0);
    bar.classList.toggle('flex', checked > 0);
    count.textContent = checked + ' seleccionado' + (checked !== 1 ? 's' : '');
}

function submitBulk(action) {
    const checked = document.querySelectorAll('.row-check:checked');
    if (!checked.length) return;

    if (action === 'reject') {
        const reason = prompt('Motivo del rechazo (opcional):');
        if (reason === null) return; // canceló
        document.getElementById('bulk-reason').value = reason;
    }

    const count = checked.length;
    const label = action === 'approve' ? 'aprobar' : 'rechazar';
    if (!confirm(`¿Confirmás ${label} los ${count} lugar(es) seleccionados?`)) return;

    document.getElementById('bulk-action').value = action;
    document.getElementById('bulk-form').submit();
}

function confirmReject(form) {
    const reason = prompt('Motivo del rechazo (opcional):');
    if (reason === null) return false;
    form.querySelector('.reject-reason-input').value = reason;
    return true;
}
</script>

</body>
</html>
