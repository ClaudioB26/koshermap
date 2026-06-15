{{-- Filtros tipo Mercado Libre: categoría, marca y tipo (kosher status) --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-5">

    {{-- País --}}
    @if($countryFacets->isNotEmpty())
    <div>
        <h3 class="text-sm font-bold text-gray-700 mb-2">País</h3>
        <ul class="space-y-1 text-sm">
            @if($selectedCountry)
            <li>
                <a href="{{ route('home', array_merge(request()->except(['country', 'page']), ['country' => ''])) }}"
                   class="text-blue-600 hover:underline">🌍 Todos los países</a>
            </li>
            @endif
            @foreach($countryFacets as $facet)
            @php $c = $facet['country']; @endphp
            <li>
                <a href="{{ route('home', array_merge(request()->except(['country', 'page']), ['country' => $c->slug])) }}"
                   class="flex justify-between items-center px-2 py-1 rounded-lg transition
                          {{ $selectedCountry === $c->slug ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="truncate">{{ $c->name }}</span>
                    <span class="text-xs text-gray-400 shrink-0 ml-2">({{ $facet['total'] }})</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Categoría --}}
    @if($categoryFacets->isNotEmpty() || $selectedCategoryModel)
    <div>
        <h3 class="text-sm font-bold text-gray-700 mb-2">Categoría</h3>
        <ul class="space-y-1 text-sm">
            @if($selectedCategoryModel)
            <li>
                <a href="{{ route('home', request()->except(['category', 'page'])) }}"
                   class="text-blue-600 hover:underline">✕ Quitar filtro</a>
            </li>
            @endif
            @foreach($categoryFacets as $facet)
            @php $cat = $facet['category']; @endphp
            <li>
                <a href="{{ route('home', array_merge(request()->except(['category', 'page']), ['category' => $cat->slug])) }}"
                   class="flex justify-between items-center px-2 py-1 rounded-lg transition
                          {{ $selectedCategory === $cat->slug ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="truncate">{{ $cat->name }}</span>
                    <span class="text-xs text-gray-400 shrink-0 ml-2">({{ $facet['total'] }})</span>
                </a>
            </li>
            @endforeach
        </ul>
        <a href="{{ route('categories.index') }}" class="text-xs text-blue-500 hover:underline mt-2 inline-block">Ver todas las categorías →</a>
    </div>
    @endif

    {{-- Marca --}}
    @if($brandFacets->isNotEmpty() || $selectedBrandModel)
    <div>
        <h3 class="text-sm font-bold text-gray-700 mb-2">Marca</h3>
        <ul class="space-y-1 text-sm">
            @if($selectedBrandModel)
            <li>
                <a href="{{ route('home', request()->except(['brand', 'page'])) }}"
                   class="text-blue-600 hover:underline">✕ Quitar filtro</a>
            </li>
            @endif
            @foreach($brandFacets as $facet)
            @php $brand = $facet['brand']; @endphp
            <li>
                <a href="{{ route('home', array_merge(request()->except(['brand', 'page']), ['brand' => $brand->slug])) }}"
                   class="flex justify-between items-center px-2 py-1 rounded-lg transition
                          {{ $selectedBrand === $brand->slug ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="truncate">{{ $brand->name }}</span>
                    <span class="text-xs text-gray-400 shrink-0 ml-2">({{ $facet['total'] }})</span>
                </a>
            </li>
            @endforeach
        </ul>
        <a href="{{ route('brands.index') }}" class="text-xs text-blue-500 hover:underline mt-2 inline-block">Ver todas las marcas →</a>
    </div>
    @endif

    {{-- Tipo (kosher status) --}}
    @if($tipoFacets->isNotEmpty() || $selectedTipo)
    <div>
        <h3 class="text-sm font-bold text-gray-700 mb-2">Tipo</h3>
        <ul class="space-y-1 text-sm">
            @if($selectedTipo)
            <li>
                <a href="{{ route('home', request()->except(['tipo', 'page'])) }}"
                   class="text-blue-600 hover:underline">✕ Quitar filtro</a>
            </li>
            @endif
            @foreach($tipoFacets as $facet)
            <li>
                <a href="{{ route('home', array_merge(request()->except(['tipo', 'page']), ['tipo' => $facet['tipo']])) }}"
                   class="flex justify-between items-center px-2 py-1 rounded-lg transition
                          {{ $selectedTipo === $facet['tipo'] ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span>{{ $facet['label'] }}</span>
                    <span class="text-xs text-gray-400 shrink-0 ml-2">({{ $facet['total'] }})</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
