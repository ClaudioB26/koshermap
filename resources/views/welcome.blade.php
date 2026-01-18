<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosherStatus - Buscador de Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">
        <h1 class="text-5xl font-black text-blue-600 mb-8">Kosher<span class="text-gray-800">Status</span></h1>
        
        <form action="{{ route('home') }}" method="GET" class="w-full max-w-2xl mb-12">
            <div class="relative flex items-center">
                <input type="text" name="query" value="{{ $query ?? '' }}" 
                    placeholder="Busca por producto, marca o código de barras..." 
                    class="w-full p-5 rounded-2xl border-none shadow-xl text-lg focus:ring-2 focus:ring-blue-400 outline-none">
                <button type="submit" class="absolute right-4 bg-blue-600 text-white px-6 py-2 rounded-xl font-bold">
                    Buscar
                </button>
            </div>
        </form>

        @if(isset($products) && $products->count() > 0)
            <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($products as $product)
                    <a href="/product/{{ $product->slug }}" class="bg-white p-4 rounded-xl shadow-sm hover:shadow-md border border-gray-100 flex items-center justify-between transition">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $product->brand->name ?? 'Marca' }}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                            {{ $product->kosher_status }}
                        </span>
                    </a>
                @endforeach
            </div>
        @elseif(isset($query))
            <p class="text-gray-500 text-lg">No encontramos resultados para "{{ $query }}"</p>
        @endif
    </div>
</body>
</html>