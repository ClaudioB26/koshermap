<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moderación de Comentarios — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <span class="text-xl font-black text-blue-600">Kosher<span class="text-gray-800">Status</span></span>
        <span class="text-gray-400">|</span>
        <span class="font-semibold text-gray-700">Moderación de comentarios</span>
    </div>
    <div class="flex items-center gap-4 text-sm">
        <a href="{{ route('admin.places.index') }}"  class="text-gray-500 hover:text-blue-600">📍 Lugares</a>
        <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-blue-600">🚩 Reportes</a>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf
            <button class="text-red-500 hover:text-red-700">Salir</button>
        </form>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-8">

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Panel de palabras sugeridas para bannear --}}
    @if($suggestedWords->isNotEmpty())
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-amber-800 mb-2">
            🔤 Palabras anotadas al rechazar — agregalas a <code class="bg-amber-100 px-1 rounded">NoProfanity.php</code>:
        </p>
        <div class="flex flex-wrap gap-2">
            @foreach($suggestedWords as $word)
            <span class="px-3 py-1 bg-amber-100 text-amber-900 rounded-full text-sm font-mono border border-amber-300">
                {{ $word }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex border-b border-gray-200 mb-6">
        @foreach(['pending' => '⏳ Pendientes', 'flagged' => '🚩 Con palabras feas', 'approved' => '✅ Aprobados', 'rejected' => '❌ Rechazados'] as $s => $label)
        <a href="{{ route('admin.reviews.index', ['status' => $s]) }}"
           class="px-5 py-3 font-semibold text-sm border-b-2 transition
                  {{ $status === $s ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            {{ $label }}
            <span class="ml-1.5 text-xs {{ $status === $s ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full">
                {{ $counts[$s] }}
            </span>
        </a>
        @endforeach
    </div>

    @if($reviews->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <p class="text-5xl mb-4">💬</p>
        <p class="text-lg">No hay comentarios en este estado.</p>
    </div>
    @else

    <form method="POST" action="{{ route('admin.reviews.bulk') }}" id="bulk-form">
        @csrf
        <input type="hidden" name="action" id="bulk-action" value="">

        {{-- Barra de acciones bulk --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-5 py-3 mb-4 flex items-center gap-4 flex-wrap">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" id="check-all" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                <span class="text-sm font-medium text-gray-700">Seleccionar todos</span>
            </label>

            <span id="selected-count" class="text-xs text-gray-400 hidden">
                <span id="count-num">0</span> seleccionados
            </span>

            <div id="bulk-bar" class="flex items-center gap-2 ml-auto flex-wrap" style="display:none !important">
                @if($status === 'pending')
                <button type="button" onclick="submitBulk('approve')"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                    ✅ Aprobar seleccionados
                </button>
                @endif
                {{-- Rechazar bulk: campo de nota --}}
                <div class="flex items-center gap-2">
                    <input type="text" name="rejection_note" id="bulk-note"
                           placeholder="Palabra a bannear (opcional)…"
                           class="text-sm border border-gray-300 rounded-lg px-3 py-2 w-52 focus:ring-2 focus:ring-red-400 outline-none">
                    <button type="button" onclick="submitBulk('reject')"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition">
                        ❌ Rechazar seleccionados
                    </button>
                </div>
            </div>
        </div>

        {{-- Lista de comentarios --}}
        <div class="space-y-3">
            @foreach($reviews as $review)
            @php
                $isLive = $review->status === 'pending'
                    && $review->created_at->lte(now()->subMinutes(\App\Models\Review::VISIBILITY_DELAY_MINUTES));
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex gap-4 items-start" x-data="{ rejectOpen: false }">

                {{-- Checkbox --}}
                @if($status !== 'approved')
                <input type="checkbox" name="ids[]" value="{{ $review->id }}"
                       class="review-check mt-1 w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer shrink-0">
                @else
                <div class="w-4 shrink-0"></div>
                @endif

                {{-- Contenido --}}
                <div class="flex-grow min-w-0">
                    <div class="flex flex-wrap gap-2 items-center mb-1">
                        <span class="font-semibold text-gray-800">{{ $review->author_name ?? 'Anónimo' }}</span>
                        <span class="text-yellow-400 text-sm">
                            {{ str_repeat('★', $review->rating) }}<span class="text-gray-200">{{ str_repeat('★', 5 - $review->rating) }}</span>
                        </span>
                        {{-- Badge flag de profanidad --}}
                        @if($review->flagged)
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                            🚩 Palabras feas
                        </span>
                        @endif
                        {{-- Badge "Ya visible" --}}
                        @if($isLive && !$review->flagged)
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                            🌐 Ya visible
                        </span>
                        @elseif($review->status === 'pending')
                        @php $secsLeft = now()->diffInSeconds($review->created_at->addMinutes(\App\Models\Review::VISIBILITY_DELAY_MINUTES), false); @endphp
                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                            ⏱ visible en {{ ceil($secsLeft / 60) }} min
                        </span>
                        @endif
                        <a href="{{ route('products.show', $review->product->slug ?? '#') }}" target="_blank"
                           class="text-xs text-blue-600 hover:underline truncate max-w-xs">
                            {{ $review->product->name ?? '—' }}
                        </a>
                    </div>

                    <p class="text-gray-700 text-sm leading-relaxed mb-2">{{ $review->content }}</p>

                    <div class="flex flex-wrap gap-3 text-xs text-gray-400">
                        <span>{{ $review->created_at->diffForHumans() }}</span>
                        @if($review->ip_address)
                        <span>🌐 {{ $review->ip_address }}</span>
                        @php
                            $strikes = \App\Models\Review::where('ip_address', $review->ip_address)
                                ->where('status', 'rejected')->count();
                        @endphp
                        @if($strikes > 0)
                        <span class="text-red-500 font-medium">⚠ {{ $strikes }} rechazo(s) de esta IP</span>
                        @endif
                        @endif
                        @if($review->rejection_note)
                        <span class="text-red-500">🔤 Motivo: <strong>{{ $review->rejection_note }}</strong></span>
                        @endif
                    </div>

                    {{-- Formulario inline de rechazo con nota --}}
                    <div x-show="rejectOpen" x-transition class="mt-3 flex items-center gap-2">
                        <input type="text" x-ref="noteField"
                               placeholder="Palabra a bannear (opcional)…"
                               class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 flex-grow max-w-xs focus:ring-2 focus:ring-red-400 outline-none">
                        <button type="button"
                                @click="
                                    document.getElementById('bulk-action').value='reject';
                                    document.querySelectorAll('.review-check').forEach(c=>c.checked=false);
                                    let cb = document.querySelector('[name=\'ids[]\'][value=\'{{ $review->id }}\']');
                                    if(cb) cb.checked=true;
                                    document.getElementById('bulk-note').value = $refs.noteField.value;
                                    document.getElementById('bulk-form').submit();
                                "
                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition">
                            Confirmar rechazo
                        </button>
                        <button type="button" @click="rejectOpen=false" class="text-xs text-gray-400 hover:text-gray-600">
                            Cancelar
                        </button>
                    </div>
                </div>

                {{-- Acciones individuales --}}
                @if($status !== 'approved')
                <div class="flex gap-2 shrink-0 flex-col sm:flex-row">
                    @if($status === 'pending')
                    <button type="button"
                            onclick="
                                document.getElementById('bulk-action').value='approve';
                                document.querySelectorAll('.review-check').forEach(c=>c.checked=false);
                                document.querySelector('[name=\'ids[]\'][value=\'{{ $review->id }}\']').checked=true;
                                document.getElementById('bulk-form').submit();
                            "
                            class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-semibold rounded-lg transition">
                        ✅ Aprobar
                    </button>
                    @endif
                    <button type="button" @click="rejectOpen = !rejectOpen"
                            class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition">
                        ❌ Rechazar…
                    </button>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </form>

    <div class="mt-6">{{ $reviews->links() }}</div>

    @endif
</main>

<script>
const checkAll = document.getElementById('check-all');
const bulkBar  = document.getElementById('bulk-bar');
const countNum = document.getElementById('count-num');
const countSpan = document.getElementById('selected-count');

function updateBulkBar() {
    const all     = document.querySelectorAll('.review-check');
    const checked = document.querySelectorAll('.review-check:checked').length;
    if (checked > 0) {
        bulkBar.style.removeProperty('display');
        countSpan.classList.remove('hidden');
        countNum.textContent = checked;
    } else {
        bulkBar.style.display = 'none';
        countSpan.classList.add('hidden');
    }
    if (checkAll) {
        checkAll.indeterminate = checked > 0 && checked < all.length;
        checkAll.checked = all.length > 0 && checked === all.length;
    }
}

if (checkAll) {
    checkAll.addEventListener('change', () => {
        document.querySelectorAll('.review-check').forEach(c => c.checked = checkAll.checked);
        updateBulkBar();
    });
}
document.querySelectorAll('.review-check').forEach(c => c.addEventListener('change', updateBulkBar));

function submitBulk(action) {
    const checked = document.querySelectorAll('.review-check:checked').length;
    if (checked === 0) return;
    const label = action === 'approve' ? 'aprobar' : 'rechazar';
    if (!confirm(`¿${label.charAt(0).toUpperCase() + label.slice(1)} ${checked} comentario(s)?`)) return;
    document.getElementById('bulk-action').value = action;
    document.getElementById('bulk-form').submit();
}
</script>
</body>
</html>
