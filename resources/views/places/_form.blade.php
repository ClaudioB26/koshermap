{{-- Formulario compartido entre alta (places.submit) y edicion (places.edit). --}}
@php
$citiesByCountry = $countries->mapWithKeys(fn ($c) => [
    $c->id => $c->cities->map(fn ($city) => ['id' => $city->id, 'name' => $city->name])->values(),
]);
$certifiableTypes = \App\Models\KosherPlace::CERTIFIABLE_TYPES;
$orientableTypes  = \App\Models\KosherPlace::ORIENTABLE_TYPES;
$orientations     = \App\Models\KosherPlace::orientations();

$val = fn ($field, $default = null) => old($field, isset($place) ? $place->{$field} : $default);
@endphp

<form method="POST" action="{{ $action }}"
      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5"
      x-data="{
          placeType: '{{ $val('place_type') }}',
          countryId: '{{ old('country_id', isset($place) ? $place->city->country_id : '') }}',
          certifierId: '{{ $val('certifier_id') }}',
          certifiableTypes: @js($certifiableTypes),
          orientableTypes: @js($orientableTypes),
          citiesByCountry: @js($citiesByCountry),
          get cities() { return this.citiesByCountry[this.countryId] || []; },
          get needsCertification() { return this.certifiableTypes.includes(this.placeType); },
          get needsOrientation() { return this.orientableTypes.includes(this.placeType); }
      }">
    @csrf
    @if($method ?? null)
    @method($method)
    @endif

    {{-- Datos del local --}}
    <div>
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Datos del local</h2>

        <div class="space-y-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Nombre del local *</label>
                <input type="text" name="name" value="{{ $val('name') }}" required
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Tipo de local *</label>
                <select name="place_type" x-model="placeType" required
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                    <option value="">— Seleccioná un tipo —</option>
                    @foreach($types as $key => $info)
                    <option value="{{ $key }}" {{ $val('place_type') === $key ? 'selected' : '' }}>
                        {{ $info['emoji'] }} {{ $info['label'] }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div x-show="needsOrientation" x-cloak>
                <label class="block text-sm text-gray-600 mb-1">Orientación</label>
                <select name="orientation"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                    @foreach($orientations as $key => $label)
                    <option value="{{ $key }}" {{ $val('orientation', 'orthodox') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">País *</label>
                    <select x-model="countryId" required
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                        <option value="">— Seleccioná un país —</option>
                        @foreach($countries as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Ciudad *</label>
                    <select name="city_id" required :disabled="!countryId"
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none disabled:bg-gray-50">
                        <option value="">— Seleccioná una ciudad —</option>
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" x-text="city.name" :selected="city.id == '{{ $val('city_id') }}'"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ $val('address') }}"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ $val('phone') }}"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Sitio web</label>
                    <input type="url" name="website" value="{{ $val('website') }}" placeholder="https://"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                </div>
            </div>
        </div>
    </div>

    {{-- Certificación kosher --}}
    <div x-show="needsCertification" x-cloak>
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Certificación kosher</h2>
        <div class="space-y-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Certificadora</label>
                <select name="certifier_id" x-model="certifierId"
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                    <option value="">— Seleccioná tu certificadora —</option>
                    @foreach($certifiers as $cert)
                    <option value="{{ $cert->id }}">{{ $cert->name }}</option>
                    @endforeach
                    <option value="other">Otra (especificar)</option>
                </select>
            </div>
            <div x-show="certifierId === 'other'" x-cloak>
                <label class="block text-sm text-gray-600 mb-1">Nombre de la certificadora</label>
                <input type="text" name="certifier_other" value="{{ $val('certifier_other') }}"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
        </div>
    </div>

    {{-- Datos de contacto --}}
    <div>
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Tus datos de contacto</h2>
        <p class="text-xs text-gray-400 mb-3">No se publican — son solo para que podamos contactarte si tenemos dudas.</p>
        <div class="space-y-3">
            <div class="text-sm text-gray-600 bg-gray-50 border border-gray-100 rounded-lg px-3 py-2">
                {{ auth()->user()->name }} · {{ auth()->user()->email }}
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Tu teléfono</label>
                <input type="text" name="owner_phone" value="{{ $val('owner_phone') }}"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
        </div>
    </div>

    {{-- Términos --}}
    <div class="flex items-start gap-2">
        <input type="checkbox" name="terms" id="terms" value="1" required {{ isset($place) ? 'checked' : '' }}
               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-300">
        <label for="terms" class="text-xs text-gray-500">
            Entiendo que KosherStatus revisará esta información antes de publicarla y se reserva el
            derecho de admisión y publicación, incluida la verificación de la certificación informada.
        </label>
    </div>

    <button type="submit"
            class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
        {{ $submitLabel }}
    </button>
</form>
