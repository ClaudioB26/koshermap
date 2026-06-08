<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — KosherStatus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-sm">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-blue-600">Kosher<span class="text-gray-800">Status</span></h1>
        <p class="text-gray-500 text-sm mt-1">Panel de administración</p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-6">Iniciar sesión</h2>

        @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Contraseña</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded">
                <label for="remember" class="text-sm text-gray-500">Recordarme</label>
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition text-sm">
                Entrar
            </button>
        </form>
    </div>
</div>

</body>
</html>
