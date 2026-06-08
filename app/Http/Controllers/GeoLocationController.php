<?php

namespace App\Http\Controllers;

use App\Services\GeoLocationService;
use App\Http\Middleware\GeoLocationMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class GeoLocationController extends Controller
{
    protected $geoService;
    protected $geoMiddleware;

    public function __construct(GeoLocationService $geoService, GeoLocationMiddleware $geoMiddleware)
    {
        $this->geoService = $geoService;
        $this->geoMiddleware = $geoMiddleware;
    }

    /**
     * Establecer preferencia de país manualmente
     */
    public function setCountry(Request $request, $countryCode)
    {
        // Validar código de país
        $supportedCountries = array_keys($this->geoService->getCountryCertifiers());
        
        if (!in_array($countryCode, $supportedCountries)) {
            return back()->with('error', 'País no soportado. Por favor selecciona un país de la lista.');
        }

        try {
            // Actualizar preferencia manual
            $this->geoMiddleware->updateCountryPreference($countryCode);
            
            // Obtener certificadoras locales
            $localCertifiers = $this->geoService->getLocalCertifiers($countryCode);
            $redirectUrl = route('certifiers.show', $localCertifiers[0]);

            Log::info('Country preference set manually', [
                'country_code' => $countryCode,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            return redirect($redirectUrl)->with('success', 
                "Hemos actualizado tu preferencia a {$this->getCountryName($countryCode)}. " .
                "Mostrándote contenido kosher local."
            );

        } catch (\Exception $e) {
            Log::error('Error setting country preference: ' . $e->getMessage());
            return back()->with('error', 'No pudimos actualizar tu preferencia. Por favor intenta nuevamente.');
        }
    }

    /**
     * Limpiar preferencia de país (volver a detección automática)
     */
    public function clearCountry(Request $request)
    {
        try {
            $this->geoMiddleware->clearCountryPreference();
            
            Log::info('Country preference cleared', [
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            return redirect('/')->with('success', 
                'Hemos restablecido la detección automática de tu ubicación.'
            );

        } catch (\Exception $e) {
            Log::error('Error clearing country preference: ' . $e->getMessage());
            return back()->with('error', 'No pudimos restablecer tu preferencia. Por favor intenta nuevamente.');
        }
    }

    /**
     * Mostrar página de selección de país
     */
    public function selectCountry()
    {
        $currentPreference = $this->geoMiddleware->getCurrentCountryPreference(request());
        $supportedCountries = $this->geoService->getCountryCertifiers();
        
        $countries = [];
        foreach ($supportedCountries as $code => $certifiers) {
            $countries[$code] = [
                'name' => $this->getCountryName($code),
                'code' => $code,
                'certifiers' => $certifiers,
                'flag' => $this->getCountryFlag($code)
            ];
        }

        return view('geo.select-country', compact('countries', 'currentPreference'));
    }

    /**
     * API para obtener información de geolocalización actual
     */
    public function getCurrentLocation(Request $request)
    {
        try {
            $currentPreference = $this->geoMiddleware->getCurrentCountryPreference($request);
            $recommendedCertifiers = $this->geoMiddleware->getRecommendedCertifiers($request);
            
            // Si no hay preferencia, detectar país actual
            if (!$currentPreference) {
                $countryData = $this->geoService->detectCountry();
                $currentPreference = [
                    'country_code' => $countryData['country_code'],
                    'source' => 'auto',
                    'country_name' => $countryData['country_name']
                ];
            }

            return response()->json([
                'current_preference' => $currentPreference,
                'recommended_certifiers' => $recommendedCertifiers,
                'is_relevant_content' => $this->geoMiddleware->isViewingRelevantContent(
                    $request, 
                    $request->get('current_certifier_slug', 'ou')
                ),
                'geo_message' => $this->geoMiddleware->getGeoLocationMessage($request)
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting current location: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'No pudimos determinar tu ubicación',
                'fallback_certifiers' => ['ou']
            ], 500);
        }
    }

    /**
     * API para obtener certificadoras locales para un país
     */
    public function getLocalCertifiers($countryCode)
    {
        $certifiers = $this->geoService->getLocalCertifiers($countryCode);
        
        return response()->json([
            'country_code' => $countryCode,
            'country_name' => $this->getCountryName($countryCode),
            'certifiers' => $certifiers,
            'certifier_details' => \App\Models\Certifier::whereIn('slug', $certifiers)->get()
        ]);
    }

    /**
     * Verificar si el contenido es relevante para el usuario
     */
    public function checkContentRelevance(Request $request, $certifierSlug)
    {
        $isRelevant = $this->geoMiddleware->isViewingRelevantContent($request, $certifierSlug);
        $recommendedCertifiers = $this->geoMiddleware->getRecommendedCertifiers($request);
        
        return response()->json([
            'is_relevant' => $isRelevant,
            'current_certifier' => $certifierSlug,
            'recommended_certifiers' => $recommendedCertifiers,
            'message' => $isRelevant ? 
                'Este contenido es relevante para tu ubicación' : 
                'Hay contenido kosher más relevante para tu región'
        ]);
    }

    /**
     * Obtener estadísticas de geolocalización (solo para admin)
     */
    public function getStats(Request $request)
    {
        // Solo permitir acceso administrativo
        if (!$this->isAdmin($request)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = $this->geoService->getGeoStats();
        
        return response()->json($stats);
    }

    /**
     * Limpiar caché de geolocalización (solo para admin)
     */
    public function clearCache(Request $request)
    {
        // Solo permitir acceso administrativo
        if (!$this->isAdmin($request)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $this->geoService->clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Caché de geolocalización limpiado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'No pudimos limpiar el caché: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener nombre del país por código
     */
    private function getCountryName($code)
    {
        $countries = [
            'AR' => 'Argentina',
            'MX' => 'México',
            'US' => 'Estados Unidos',
            'CA' => 'Canadá',
            'IL' => 'Israel',
            'BR' => 'Brasil',
            'UY' => 'Uruguay',
            'CL' => 'Chile',
            'CO' => 'Colombia'
        ];

        return $countries[$code] ?? 'Desconocido';
    }

    /**
     * Obtener emoji de bandera del país
     */
    private function getCountryFlag($code)
    {
        $flags = [
            'AR' => 'Argentina',
            'MX' => 'México',
            'US' => 'Estados Unidos',
            'CA' => 'Canadá',
            'IL' => 'Israel',
            'BR' => 'Brasil',
            'UY' => 'Uruguay',
            'CL' => 'Chile',
            'CO' => 'Colombia'
        ];

        return $flags[$code] ?? '??';
    }

    /**
     * Verificar si es usuario administrativo
     */
    private function isAdmin(Request $request)
    {
        // Lógica simple para verificar admin (ajustar según necesidad)
        return $request->ip() === '127.0.0.1' || 
               $request->user()?->admin === true ||
               app()->environment('local');
    }
}
