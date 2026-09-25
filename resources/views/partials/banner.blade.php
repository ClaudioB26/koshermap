{{-- Banner propio (reemplaza a AdSense). Se administra en /admin/banners.
     Uso: @include('partials.banner', ['slot' => 'top'|'bottom']). No muestra
     nada si no hay un banner vigente para ese lugar y esa pagina. --}}
@php $banner = app(\App\Services\BannerService::class)->pick($slot); @endphp
@if($banner)
<div class="container mx-auto px-4 {{ $slot === 'top' ? 'pt-3' : 'mb-2' }}">
    <div class="mx-auto max-w-[970px]">
        <a href="{{ route('banners.click', $banner) }}" target="_blank" rel="sponsored nofollow noopener" class="block">
            <img src="{{ route('banners.image', $banner) }}"
                 alt="{{ $banner->alt ?: $banner->name }}"
                 @if($banner->width && $banner->height) width="{{ $banner->width }}" height="{{ $banner->height }}" @endif
                 class="w-full h-auto rounded-lg border border-gray-200" loading="lazy">
        </a>
        <p class="text-[10px] text-gray-400 text-right mt-0.5">Publicidad</p>
    </div>
</div>
@endif
