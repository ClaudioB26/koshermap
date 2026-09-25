{{-- Widget de Cloudflare Turnstile. No se muestra si no hay TURNSTILE_SITE_KEY
     (local, o antes de cargar las claves en el .env). La validacion del lado
     del servidor esta en App\Rules\Turnstile. --}}
@if(config('services.turnstile.site_key'))
<div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
@once
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endonce
@endif
