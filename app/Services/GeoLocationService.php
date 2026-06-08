<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class GeoLocationService
{
    private $apiKey;
    private $cacheDuration = 86400; // 24 horas

    public function __construct()
    {
        $this->apiKey = config('services.ipstack.api_key') ?? null;
    }

    /**
     * Detectar país del visitante por IP
     */
    public function detectCountry($ipAddress = null)
    {
        // Si no se proporciona IP, usar la IP del visitante actual
        if (!$ipAddress) {
            $ipAddress = $this->getClientIP();
        }

        // Si es IP local o de desarrollo, usar IP de prueba
        if ($this->isLocalIP($ipAddress)) {
            return $this->getTestCountryData();
        }

        // Intentar desde caché primero
        $cacheKey = "geo_location_{$ipAddress}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $countryData = $this->fetchFromAPI($ipAddress);
            
            if ($countryData) {
                Cache::put($cacheKey, $countryData, $this->cacheDuration);
                return $countryData;
            }
        } catch (\Exception $e) {
            Log::error('Error detecting country from IP: ' . $e->getMessage());
        }

        // Fallback a datos de prueba si falla
        return $this->getTestCountryData();
    }

    /**
     * Obtener IP del cliente
     */
    private function getClientIP()
    {
        $request = request();
        
        // Verificar si estamos en desarrollo local
        if ($request->is('localhost/*') || $request->ip() === '127.0.0.1') {
            return null; // Usar datos de prueba
        }

        return $request->ip();
    }

    /**
     * Verificar si es IP local
     */
    private function isLocalIP($ip)
    {
        return in_array($ip, ['127.0.0.1', '::1', 'localhost']) || 
               str_starts_with($ip, '192.168.') || 
               str_starts_with($ip, '10.') || 
               str_starts_with($ip, '172.16.');
    }

    /**
     * Obtener datos de país desde API
     */
    private function fetchFromAPI($ipAddress)
    {
        // Intentar con ipstack API
        if ($this->apiKey) {
            return $this->fetchFromIpstack($ipAddress);
        }

        // Fallback a ip-api.com (gratis)
        return $this->fetchFromIpApi($ipAddress);
    }

    /**
     * Usar ipstack API
     */
    private function fetchFromIpstack($ipAddress)
    {
        $response = Http::timeout(10)
            ->get("http://api.ipstack.com/{$ipAddress}", [
                'access_key' => $this->apiKey,
                'fields' => 'country_name,country_code,region_name,city,zip,latitude,longitude'
            ]);

        if ($response->successful()) {
            $data = $response->json();
            
            return [
                'country_name' => $data['country_name'] ?? 'Unknown',
                'country_code' => $data['country_code'] ?? 'XX',
                'region_name' => $data['region_name'] ?? null,
                'city' => $data['city'] ?? null,
                'zip' => $data['zip'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'ip' => $ipAddress,
                'source' => 'ipstack'
            ];
        }

        return null;
    }

    /**
     * Usar ip-api.com (API gratuita)
     */
    private function fetchFromIpApi($ipAddress)
    {
        $response = Http::timeout(10)
            ->get("http://ip-api.com/json/{$ipAddress}", [
                'fields' => 'country,countryCode,region,city,zip,lat,lon'
            ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['status'] === 'success') {
                return [
                    'country_name' => $data['country'] ?? 'Unknown',
                    'country_code' => $data['countryCode'] ?? 'XX',
                    'region_name' => $data['region'] ?? null,
                    'city' => $data['city'] ?? null,
                    'zip' => $data['zip'] ?? null,
                    'latitude' => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                    'ip' => $ipAddress,
                    'source' => 'ip-api'
                ];
            }
        }

        return null;
    }

    /**
     * Datos de prueba para desarrollo local
     */
    private function getTestCountryData()
    {
        // Simular diferentes países para pruebas
        $testCountries = [
            [
                'country_name' => 'Argentina',
                'country_code' => 'AR',
                'region_name' => 'Buenos Aires',
                'city' => 'Buenos Aires',
                'latitude' => -34.6037,
                'longitude' => -58.3816,
                'ip' => '127.0.0.1',
                'source' => 'test'
            ],
            [
                'country_name' => 'Mexico',
                'country_code' => 'MX',
                'region_name' => 'Ciudad de Mexico',
                'city' => 'Mexico City',
                'latitude' => 19.4326,
                'longitude' => -99.1332,
                'ip' => '127.0.0.1',
                'source' => 'test'
            ],
            [
                'country_name' => 'United States',
                'country_code' => 'US',
                'region_name' => 'New York',
                'city' => 'New York',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'ip' => '127.0.0.1',
                'source' => 'test'
            ]
        ];

        // Rotar entre países de prueba para simular diferentes ubicaciones
        $index = (date('H') % count($testCountries));
        return $testCountries[$index];
    }

    /**
     * Obtener certificadoras locales para un país
     */
    public function getLocalCertifiers($countryCode)
    {
        // Mapeo de certificadoras por país
        $countryCertifiers = [
            'AR' => ['ajdut-kosher'], // Argentina -> Ajdut Kosher
            'MX' => ['kmd-mexico'],    // Mexico -> KMD
            'US' => ['ou'],            // Estados Unidos -> OU
            'CA' => ['ou'],            // Canada -> OU
            'IL' => ['ou'],            // Israel -> OU
            'BR' => ['ou'],            // Brasil -> OU (por ahora)
            'UY' => ['ajdut-kosher'], // Uruguay -> Ajdut Kosher (cercanía)
            'CL' => ['ou'],            // Chile -> OU (por ahora)
            'CO' => ['ou'],            // Colombia -> OU (por ahora)
        ];

        return $countryCertifiers[$countryCode] ?? ['ou']; // Default a OU
    }

    /**
     * Verificar si el usuario debe ser redirigido a contenido local
     */
    public function shouldRedirectToLocal($detectedCountry, $currentCountry = null)
    {
        // Si ya hay un país seleccionado manualmente, no redirigir
        if ($currentCountry && $currentCountry !== 'auto') {
            return false;
        }

        // Obtener certificadoras locales
        $localCertifiers = $this->getLocalCertifiers($detectedCountry['country_code']);

        // Verificar si hay productos de certificadoras locales
        $hasLocalProducts = \App\Models\Product::whereIn('certifier_id', 
            \App\Models\Certifier::whereIn('slug', $localCertifiers)->pluck('id')
        )->exists();

        return $hasLocalProducts;
    }

    /**
     * Obtener URL de redirección según país
     */
    public function getRedirectUrl($countryData)
    {
        $countryCode = $countryData['country_code'];
        $localCertifiers = $this->getLocalCertifiers($countryCode);

        // Priorizar la primera certificadora local
        $certifierSlug = $localCertifiers[0] ?? 'ou';

        return route('certifiers.show', $certifierSlug);
    }

    /**
     * Limpiar caché de geolocalización
     */
    public function clearCache($ipAddress = null)
    {
        if ($ipAddress) {
            Cache::forget("geo_location_{$ipAddress}");
        } else {
            // Limpiar todo el caché de geolocalización
            $keys = Cache::getMemoryUsage()['keys'] ?? [];
            foreach ($keys as $key) {
                if (str_starts_with($key, 'geo_location_')) {
                    Cache::forget($key);
                }
            }
        }
    }

    /**
     * Obtener estadísticas de geolocalización
     */
    public function getGeoStats()
    {
        return [
            'cache_duration' => $this->cacheDuration,
            'api_key_configured' => !empty($this->apiKey),
            'supported_countries' => count($this->getCountryCertifiers()),
            'test_mode' => $this->isLocalIP($this->getClientIP())
        ];
    }

    /**
     * Obtener todos los mapeos de países
     */
    public function getCountryCertifiers()
    {
        return [
            'AR' => ['ajdut-kosher'],
            'MX' => ['kmd-mexico'],
            'US' => ['ou'],
            'CA' => ['ou'],
            'IL' => ['ou'],
            'BR' => ['ou'],
            'UY' => ['ajdut-kosher'],
            'CL' => ['ou'],
            'CO' => ['ou'],
        ];
    }
}
