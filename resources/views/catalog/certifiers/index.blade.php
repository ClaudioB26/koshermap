@extends('layouts.app')

@section('title', 'Certificadoras - KosherStatus')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-center text-blue-800">{{ __('Kosher Certifiers') }}</h1>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($certifiers as $certifier)
        <a href="{{ route('certifiers.show', $certifier->slug) }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition text-center border border-gray-100">
            <h2 class="text-xl font-semibold">{{ $certifier->name }}</h2>
            <div class="text-gray-500 text-sm mt-1">{{ $certifier->logo_symbol ?? 'N/A' }}</div>
        </a>
        @endforeach
    </div>
@endsection
