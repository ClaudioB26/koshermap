<?php

namespace App\Http\Middleware;

use App\Services\GeoLocationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class GeoLocationMiddleware
{
    protected $geoService;
    protected $cookieName = 'user_country_preference';
    protected $cookieDuration = 525600; // 1 año en minutos

    public function __construct(GeoLocationService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar en la primera visita o cuando no hay preferencia guardada
        if ($this->shouldApplyGeoLogic($request)) {
            $this->processGeoLocation($request);
        }

        return $next($request);
    }

    /**
     * Determinar si se debe aplicar la lógica de geolocalización
     */
    private function shouldApplyGeoLogic(Request $request): bool
    {
        // No aplicar en rutas AJAX, API o de administración
        if ($request->ajax() || $request->is('api/*') || $request->is('admin/*')) {
            return false;
        }

        // No aplicar si ya hay una preferencia de país manual
        if ($this->hasManualCountryPreference($request)) {
            return false;
        }

        // Aplicar solo en la primera visita o si no hay cookie
        return !$request->cookie($this->cookieName) || $request->cookie($this->cookieName) === 'auto';
    }

    /**
     * Procesar la lógica de geolocalización
     */
    private function processGeoLocation(Request $request): void
    {
        try {
            // Detectar país del visitante
            $countryData = $this->geoService->detectCountry();
            
            Log::info('GeoLocation detected', [
                'country' => $countryData['country_name'],
                'code' => $countryData['country_code'],
                'ip' => $countryData['ip'],
                'source' => $countryData['source']
            ]);

            // Guardar preferencia automática en cookie
            $this->setCountryCookie($countryData['country_code'], 'auto');

            // Verificar si debe redirigir a contenido local
            if ($this->geoService->shouldRedirectToLocal($countryData)) {
                $redirectUrl = $this->geoService->getRedirectUrl($countryData);
                
                Log::info('GeoLocation redirect', [
                    'from' => $request->fullUrl(),
                    'to' => $redirectUrl,
                    'country' => $countryData['country_name']
                ]);

                // Redirigir con mensaje informativo
                $this->redirectToLocalContent($redirectUrl, $countryData);
            }

        } catch (\Exception $e) {
            Log::error('Error in GeoLocation middleware: ' . $e->getMessage());
            // No interrumpir la experiencia del usuario si falla la geolocalización
        }
    }

    /**
     * Verificar si hay preferencia manual de país
     */
    private function hasManualCountryPreference(Request $request): bool
    {
        $countryCookie = $request->cookie($this->cookieName);
        
        // Si la cookie existe y no es 'auto', es una preferencia manual
        return $countryCookie && $countryCookie !== 'auto';
    }

    /**
     * Establecer cookie de preferencia de país
     */
    public function setCountryCookie(string $countryCode, string $source = 'manual'): void
    {
        $cookieValue = json_encode([
            'country_code' => $countryCode,
            'source' => $source, // 'auto' o 'manual'
            'timestamp' => now()->timestamp
        ]);

        Cookie::queue(
            $this->cookieName, 
            $cookieValue, 
            $this->cookieDuration,
            null, // path
            null, // domain
            true, // secure (solo HTTPS)
            false, // httpOnly (permitir acceso desde JavaScript)
            false, // raw
            'Lax' // sameSite
        );
    }

    /**
     * Redirigir a contenido local con mensaje informativo
     */
    private function redirectToLocalContent(string $url, array $countryData): void
    {
        // Agregar mensaje flash para mostrar al usuario
        session()->flash('geo_redirect', [
            'detected_country' => $countryData['country_name'],
            'redirect_reason' => 'Te hemos redirigido a contenido kosher de tu región'
        ]);

        Redirect::to($url)->send();
        exit;
    }

    /**
     * Obtener preferencia de país actual
     */
    public function getCurrentCountryPreference(Request $request): ?array
    {
        $countryCookie = $request->cookie($this->cookieName);
        
        if ($countryCookie) {
            try {
                $preference = json_decode($countryCookie, true);
                
                return [
                    'country_code' => $preference['country_code'] ?? null,
                    'source' => $preference['source'] ?? 'unknown',
                    'timestamp' => $preference['timestamp'] ?? null
                ];
            } catch (\Exception $e) {
                Log::error('Error parsing country cookie: ' . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Limpiar preferencia de país
     */
    public function clearCountryPreference(): void
    {
        Cookie::forget($this->cookieName);
    }

    /**
     * Actualizar preferencia de país manualmente
     */
    public function updateCountryPreference(string $countryCode): void
    {
        $this->setCountryCookie($countryCode, 'manual');
        
        Log::info('Country preference updated manually', [
            'country_code' => $countryCode,
            'source' => 'manual'
        ]);
    }

    /**
     * Obtener certificadoras recomendadas para el usuario actual
     */
    public function getRecommendedCertifiers(Request $request): array
    {
        $preference = $this->getCurrentCountryPreference($request);
        
        if ($preference && $preference['country_code']) {
            return $this->geoService->getLocalCertifiers($preference['country_code']);
        }

        // Si no hay preferencia, detectar país actual
        try {
            $countryData = $this->geoService->detectCountry();
            return $this->geoService->getLocalCertifiers($countryData['country_code']);
        } catch (\Exception $e) {
            // Fallback a certificadoras globales
            return ['ou'];
        }
    }

    /**
     * Verificar si el usuario está viendo contenido relevante para su ubicación
     */
    public function isViewingRelevantContent(Request $request, string $currentCertifierSlug): bool
    {
        $recommendedCertifiers = $this->getRecommendedCertifiers($request);
        
        return in_array($currentCertifierSlug, $recommendedCertifiers);
    }

    /**
     * Obtener mensaje de geolocalización para mostrar al usuario
     */
    public function getGeoLocationMessage(Request $request): ?array
    {
        if (session()->has('geo_redirect')) {
            return session('geo_redirect');
        }

        return null;
    }
}
