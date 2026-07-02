@extends('layouts.app')

@section('title', 'Rubros de Alimentos Kosher - KosherMap')
@section('meta_description', 'Explorá todas las categorías de alimentos kosher: lácteos, carnes, bebidas, panadería, snacks y más. Cada rubro incluye productos certificados por las principales agencias kosher del mundo.')

@section('content')
    <h1 class="text-3xl font-bold mb-4 text-center text-blue-800">{{ __('Food Categories') }}</h1>

    <div class="max-w-3xl mx-auto mb-8 text-gray-600 text-sm leading-relaxed">
        <p class="mb-3">KosherMap organiza su catálogo de más de 6.000 productos kosher en rubros y subrubros para facilitar tu búsqueda. Encontrá alimentos certificados por categoría: desde lácteos y carnes hasta panadería, bebidas, snacks y productos de limpieza.</p>
        <p class="mb-3">Cada categoría muestra el sello de la certificadora (OU, KMD, Ajdut, BDK y otras) para que puedas verificar la kashrut del producto antes de comprarlo. Las leyes de kashrut establecen distintas categorías para los alimentos: los productos <strong>parve</strong> (sin carne ni lácteos) pueden consumirse con cualquier comida; los <strong>lácteos</strong> (chalav) no pueden mezclarse con carne; y los productos <strong>cárnicos</strong> (basar) requieren una espera después de los lácteos según la tradición de cada comunidad.</p>
        <p>Usá los filtros de cada categoría para acotar tu búsqueda por país de origen, marca o certificadora. Si buscás un producto específico, podés usar el buscador por nombre o escanear el código de barras con la cámara de tu dispositivo.</p>
    </div>
    
    <div class="space-y-12">
        @foreach($categories as $category)
        <div class="bg-gray-50 p-6 rounded-xl">
            <a href="{{ route('categories.show', $category->slug) }}" class="block mb-4">
                <h2 class="text-2xl font-bold text-gray-800 hover:text-blue-600 transition flex items-center gap-2">
                    {{ $category->name }}
                    <span class="text-sm font-normal text-gray-500 bg-gray-200 px-2 py-1 rounded-full">{{ __('View all') }}</span>
                </h2>
            </a>
            
            @if($category->children->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($category->children as $child)
                <a href="{{ route('categories.show', $child->slug) }}" class="bg-white px-4 py-3 rounded-lg shadow-sm hover:shadow-md transition text-center border border-gray-200 text-gray-700 hover:text-blue-600 font-medium text-sm">
                    {{ $child->name }}
                </a>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 italic text-sm">{{ __('No subcategories') }}</p>
            @endif
        </div>
        @endforeach
    </div>

    <div class="max-w-3xl mx-auto mt-16">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Preguntas frecuentes sobre rubros kosher</h2>
        <div class="space-y-5 text-sm text-gray-600 leading-relaxed">
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">¿Qué diferencia hay entre parve, lácteo y cárnico?</h3>
                <p>La kashrut divide los alimentos en tres grandes grupos. Los productos <strong>parve</strong> no contienen carne ni lácteos (incluyen frutas, verduras, huevos, pescado y la mayoría de los granos) y pueden comerse con cualquier comida. Los productos <strong>lácteos</strong> contienen leche o derivados y no pueden mezclarse con carne ni consumirse juntos según las leyes de kashrut. Los productos <strong>cárnicos</strong> provienen de animales permitidos sacrificados según las reglas de shejitá y requieren una separación temporal de los lácteos que varía entre comunidades (entre 1 y 6 horas).</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">¿Puedo confiar en un producto importado sin sello kosher local?</h3>
                <p>Depende del sello que tenga. Un producto con OU (Orthodox Union), OK Kosher, Star-K o Kof-K es reconocido internacionalmente y aceptado por prácticamente todas las comunidades judías. Si el sello es de una certificadora regional desconocida en tu país, es recomendable consultar con tu rabino o autoridad kosher local antes de consumirlo. KosherMap muestra la certificadora de cada producto para ayudarte a tomar esa decisión.</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-1">¿Los productos de limpieza o cosméticos necesitan certificación kosher?</h3>
                <p>En general, los productos no comestibles no requieren certificación kosher para uso cotidiano. Sin embargo, algunos productos que entran en contacto con la boca (pasta de dientes, enjuague bucal, lápiz labial) o que se usan en la preparación de alimentos (jabón de manos, detergente) pueden necesitar supervisión, especialmente durante Pesaj, cuando las restricciones son más estrictas. Consultá con tu autoridad rabbínica si tenés dudas específicas.</p>
            </div>
        </div>
    </div>
@endsection
