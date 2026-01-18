<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - KosherStatus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-white shadow-md p-4 mb-8">
        <div class="max-w-4xl mx-auto">
            <span class="text-2xl font-bold text-blue-600">KosherStatus</span>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col md:flex-row">
            
            <!--div class="bg-gray-200 p-12 flex items-center justify-center md:w-1/3">
                <span class="text-6xl">📦</span>
            </div-->
			<div class="bg-white p-8 flex items-center justify-center md:w-1/3">
				@if($product->image_url)
					<img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-h-80 w-auto object-contain">
				@else
					<span class="text-8xl">📦</span>
				@endif
			</div>
            <div class="p-8 md:w-2/3">
                <p class="text-blue-500 font-bold uppercase tracking-widest text-sm">
                    {{ $product->brand->name ?? 'Marca Genérica' }}
                </p>
                
                <h1 class="text-4xl font-extrabold text-gray-800 mb-4">
                    {{ $product->name }}
                </h1>

                <div class="inline-block bg-blue-600 text-white px-6 py-2 rounded-full font-bold text-xl mb-6">
                    KOSHER: {{ strtoupper($product->kosher_status) }}
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Certificadora:</span>
                        <span class="font-bold">{{ $product->certifier->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-500">Barcode:</span>
                        <span class="font-mono">{{ $product->barcode ?? '0000000000' }}</span>
                    </div>
                </div>

                <p class="mt-6 text-gray-600">
                    {{ $product->description ?? 'Información verificada por el sistema KosherStatus.' }}
                </p>
            </div>
        </div>
    </main>

</body>
</html>