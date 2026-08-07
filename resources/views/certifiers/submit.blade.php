@extends('layouts.app')

@section('title', 'Dá de alta tu certificadora - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🏅 Dá de alta tu certificadora</h1>
        <p class="text-gray-500 text-sm mt-1">
            Completá los datos de tu agencia de certificación kosher. Nuestro equipo va a revisarlos
            (suele tomar alrededor de una semana) antes de aprobarla.
        </p>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
        <p class="font-semibold mb-1">Revisá los siguientes datos:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('certifiers.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm text-gray-600 mb-1">Nombre de la certificadora *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Rabino/rabinato a cargo</label>
            <input type="text" name="rabbi_name" value="{{ old('rabbi_name') }}"
                   placeholder="Ej: Rab. Juan Pérez"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Certifica desde (año)</label>
                <input type="number" name="founded_year" value="{{ old('founded_year') }}"
                       min="1900" max="{{ date('Y') }}" placeholder="Ej: 1998"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Sitio web</label>
                <input type="url" name="website" value="{{ old('website') }}" placeholder="https://"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Cobertura *</label>
            <p class="text-xs text-gray-400 mb-1">¿En qué país(es) certifica, y qué tipo de productos o establecimientos?</p>
            <textarea name="coverage_description" rows="3" required
                      class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">{{ old('coverage_description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Referencias</label>
            <p class="text-xs text-gray-400 mb-1">
                Links o datos que nos ayuden a verificarla: comunidad que la avala, otras certificadoras
                que la reconocen, listado público de productos certificados, redes sociales, etc.
            </p>
            <textarea name="reference_info" rows="3"
                      class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">{{ old('reference_info') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Documentos de respaldo</label>
            <p class="text-xs text-gray-400 mb-1">
                Adjuntá el sello de certificación, cartas de recomendación, credenciales del rabinato u otro
                material que nos ayude a verificarla. Podés subir varios archivos (PDF o imagen JPG/PNG),
                máx. 8&nbsp;MB c/u, hasta 5 en total.
            </p>
            <div id="documents-list" class="space-y-2">
                <input type="file" name="documents[]" accept=".pdf,.jpg,.jpeg,.png,.webp"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
            <button type="button" id="add-document-btn"
                    class="mt-2 text-sm text-blue-600 hover:underline">
                + Agregar otro documento
            </button>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Tus datos de contacto</h2>
            <p class="text-xs text-gray-400 mb-3">No se publican — son solo para que podamos contactarte durante la revisión.</p>
            <div class="space-y-3">
                <div class="text-sm text-gray-600 bg-gray-50 border border-gray-100 rounded-lg px-3 py-2">
                    {{ auth()->user()->name }} · {{ auth()->user()->email }}
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tu teléfono</label>
                    <input type="text" name="submitted_by_phone" value="{{ old('submitted_by_phone') }}"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="flex items-start gap-2">
            <input type="checkbox" name="terms" id="terms" value="1" required
                   class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-300">
            <label for="terms" class="text-xs text-gray-500">
                Entiendo que KosherMap va a revisar y verificar esta información antes de publicar la
                certificadora, y se reserva el derecho de admisión.
            </label>
        </div>

        <button type="submit"
                class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            Enviar para revisión
        </button>
    </form>
</div>

<script>
document.getElementById('add-document-btn').addEventListener('click', function () {
    const list = document.getElementById('documents-list');
    if (list.children.length >= 5) {
        alert('Máximo 5 documentos.');
        return;
    }
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'documents[]';
    input.accept = '.pdf,.jpg,.jpeg,.png,.webp';
    input.className = 'w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none';
    list.appendChild(input);
});
</script>

@endsection
