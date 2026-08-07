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

<form method="POST" action="{{ $action }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div>
        <label class="block text-sm text-gray-600 mb-1">Nombre del producto *</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Marca</label>
            <input type="text" name="brand_name" value="{{ old('brand_name', $product->brand->name ?? '') }}"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Código de barras</label>
            <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Categoría</label>
            <select name="category_id"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                <option value="">— Sin categoría —</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Estado kosher *</label>
            <select name="kosher_status" required
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                <option value="">— Seleccioná —</option>
                @foreach($statuses as $st)
                <option value="{{ $st }}" {{ old('kosher_status', $product->kosher_status) === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm text-gray-600 mb-1">Imagen (URL)</label>
        <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://"
               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
    </div>

    <div>
        <label class="block text-sm text-gray-600 mb-1">Descripción</label>
        <textarea name="description" rows="3"
                  class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">{{ old('description', $product->description) }}</textarea>
    </div>

    <button type="submit"
            class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
        {{ $submitLabel }}
    </button>
</form>
