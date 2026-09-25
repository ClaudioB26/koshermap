@extends('layouts.admin-panel')
@section('title', $banner->exists ? 'Editar banner' : 'Nuevo banner')
@section('content')
@php $v = fn ($f, $d = null) => old($f, $banner->{$f} ?? $d); @endphp
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $banner->exists ? '✏️ Editar banner' : '➕ Nuevo banner' }}</h1>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf
        @if($banner->exists) @method('PUT') @endif

        @php $input = 'w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none'; @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Nombre de la campaña *</label>
                <input type="text" name="name" value="{{ $v('name') }}" required class="{{ $input }}" placeholder="Ej: Kosher Deli octubre">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Anunciante</label>
                <input type="text" name="advertiser" value="{{ $v('advertiser') }}" class="{{ $input }}" placeholder="Quién paga">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Imagen del banner {{ $banner->exists ? '' : '*' }}</label>
            @if($banner->exists)
            <img src="{{ route('banners.image', $banner) }}" alt="" class="w-64 h-auto rounded border border-gray-200 mb-2">
            <p class="text-xs text-gray-400 mb-1">Si subís otra imagen, reemplaza a esta.</p>
            @endif
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" {{ $banner->exists ? '' : 'required' }} class="{{ $input }}">
            <p class="text-xs text-gray-400 mt-1">
                Tamaño recomendado: <strong>970×90</strong> o <strong>728×90</strong> px (se achica solo en celular).
                JPG, PNG, WebP o GIF, hasta 2&nbsp;MB, mínimo 300×50.
            </p>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Link de destino *</label>
            <input type="url" name="target_url" value="{{ $v('target_url') }}" required placeholder="https://" class="{{ $input }}">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Texto alternativo</label>
            <input type="text" name="alt" value="{{ $v('alt') }}" class="{{ $input }}" placeholder="Describe la imagen (accesibilidad). Si lo dejás vacío se usa el nombre.">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Lugar *</label>
                <select name="slot" class="{{ $input }}">
                    @foreach(\App\Models\Banner::SLOTS as $k => $label)
                    <option value="{{ $k }}" @selected($v('slot') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Páginas *</label>
                <select name="pages" class="{{ $input }}">
                    @foreach(\App\Models\Banner::PAGES as $k => $label)
                    <option value="{{ $k }}" @selected($v('pages') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Empieza</label>
                <input type="date" name="starts_on" value="{{ old('starts_on', $banner->starts_on?->format('Y-m-d')) }}" class="{{ $input }}">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Termina</label>
                <input type="date" name="ends_on" value="{{ old('ends_on', $banner->ends_on?->format('Y-m-d')) }}" class="{{ $input }}">
            </div>
        </div>
        <p class="text-xs text-gray-400 -mt-3">Vacío = sin límite. El banner se apaga solo cuando pasa la fecha de fin.</p>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Notas internas</label>
            <textarea name="notes" rows="3" class="{{ $input }}" placeholder="Precio acordado, contacto, condiciones… (no se muestra en el sitio)">{{ $v('notes') }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true)) class="rounded border-gray-300 text-blue-600">
            Activo (se muestra si está dentro de sus fechas)
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                {{ $banner->exists ? 'Guardar cambios' : 'Crear banner' }}
            </button>
            <a href="{{ route('admin.banners.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>
@endsection
