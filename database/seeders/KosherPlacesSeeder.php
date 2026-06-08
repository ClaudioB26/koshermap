<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class KosherPlacesSeeder extends Seeder
{
    public function run(): void
    {
        // Actualizar/crear países con locale
        $locales = [
            'AR' => 'es', 'IL' => 'he', 'US' => 'en', 'BR' => 'pt',
            'MX' => 'es', 'UY' => 'es', 'CL' => 'es', 'FR' => 'fr',
            'DE' => 'de', 'GB' => 'en',
        ];

        $extraCountries = [
            'IL' => 'Israel',
            'US' => 'Estados Unidos',
            'BR' => 'Brasil',
            'MX' => 'México',
            'UY' => 'Uruguay',
            'CL' => 'Chile',
            'FR' => 'Francia',
            'DE' => 'Alemania',
            'GB' => 'Reino Unido',
        ];

        foreach ($extraCountries as $code => $name) {
            Country::firstOrCreate(['code' => $code], [
                'name'   => $name,
                'slug'   => strtolower($code),
                'locale' => $locales[$code],
            ]);
        }

        // Actualizar locales en países que ya existen
        foreach ($locales as $code => $locale) {
            Country::where('code', $code)->update(['locale' => $locale]);
        }

        $get = fn($code) => Country::where('code', $code)->first();

        $cities = [
            // ── ARGENTINA ──────────────────────────────────────────────────
            // major=60d, large=90d, medium=180d, small=270d, tiny=365d
            ['country' => 'AR', 'name' => 'Buenos Aires',  'state' => 'CABA',          'lat' => -34.6037, 'lng' => -58.3816, 'radius' => 25000, 'density' => 'major'],
            ['country' => 'AR', 'name' => 'Rosario',       'state' => 'Santa Fe',       'lat' => -32.9468, 'lng' => -60.6393, 'radius' => 15000, 'density' => 'large'],
            ['country' => 'AR', 'name' => 'Córdoba',       'state' => 'Córdoba',        'lat' => -31.4201, 'lng' => -64.1888, 'radius' => 15000, 'density' => 'large'],
            ['country' => 'AR', 'name' => 'Mar del Plata', 'state' => 'Buenos Aires',   'lat' => -38.0055, 'lng' => -57.5426, 'radius' => 10000, 'density' => 'medium'],
            ['country' => 'AR', 'name' => 'Mendoza',       'state' => 'Mendoza',        'lat' => -32.8908, 'lng' => -68.8272, 'radius' => 10000, 'density' => 'small'],
            ['country' => 'AR', 'name' => 'Tucumán',       'state' => 'Tucumán',        'lat' => -26.8083, 'lng' => -65.2176, 'radius' => 10000, 'density' => 'small'],
            ['country' => 'AR', 'name' => 'La Plata',      'state' => 'Buenos Aires',   'lat' => -34.9215, 'lng' => -57.9545, 'radius' => 10000, 'density' => 'small'],
            ['country' => 'AR', 'name' => 'Bahía Blanca',  'state' => 'Buenos Aires',   'lat' => -38.7183, 'lng' => -62.2661, 'radius' =>  8000, 'density' => 'tiny'],
            // ── ISRAEL ─────────────────────────────────────────────────────
            ['country' => 'IL', 'name' => 'Tel Aviv',      'state' => null,             'lat' => 32.0853,  'lng' => 34.7818,  'radius' => 20000, 'density' => 'major'],
            ['country' => 'IL', 'name' => 'Jerusalem',     'state' => null,             'lat' => 31.7683,  'lng' => 35.2137,  'radius' => 20000, 'density' => 'major'],
            ['country' => 'IL', 'name' => 'Haifa',         'state' => null,             'lat' => 32.7940,  'lng' => 34.9896,  'radius' => 15000, 'density' => 'large'],
            // ── ESTADOS UNIDOS ─────────────────────────────────────────────
            ['country' => 'US', 'name' => 'New York',      'state' => 'New York',       'lat' => 40.7128,  'lng' => -74.0060, 'radius' => 30000, 'density' => 'major'],
            ['country' => 'US', 'name' => 'Los Angeles',   'state' => 'California',     'lat' => 34.0522,  'lng' => -118.2437,'radius' => 25000, 'density' => 'major'],
            ['country' => 'US', 'name' => 'Miami',         'state' => 'Florida',        'lat' => 25.7617,  'lng' => -80.1918, 'radius' => 20000, 'density' => 'large'],
            ['country' => 'US', 'name' => 'Chicago',       'state' => 'Illinois',       'lat' => 41.8781,  'lng' => -87.6298, 'radius' => 20000, 'density' => 'large'],
            // ── BRASIL ─────────────────────────────────────────────────────
            ['country' => 'BR', 'name' => 'São Paulo',     'state' => 'São Paulo',      'lat' => -23.5505, 'lng' => -46.6333, 'radius' => 25000, 'density' => 'major'],
            ['country' => 'BR', 'name' => 'Rio de Janeiro','state' => 'Rio de Janeiro', 'lat' => -22.9068, 'lng' => -43.1729, 'radius' => 20000, 'density' => 'large'],
            ['country' => 'BR', 'name' => 'Porto Alegre',  'state' => 'Rio Grande do Sul','lat'=> -30.0346, 'lng' => -51.2177, 'radius' => 12000, 'density' => 'medium'],
            // ── LATINOAMÉRICA ──────────────────────────────────────────────
            ['country' => 'UY', 'name' => 'Montevideo',    'state' => null,             'lat' => -34.9011, 'lng' => -56.1645, 'radius' => 15000, 'density' => 'large'],
            ['country' => 'CL', 'name' => 'Santiago',      'state' => 'RM',             'lat' => -33.4489, 'lng' => -70.6693, 'radius' => 20000, 'density' => 'large'],
            ['country' => 'MX', 'name' => 'Ciudad de México','state' => null,           'lat' => 19.4326,  'lng' => -99.1332, 'radius' => 25000, 'density' => 'large'],
            // ── EUROPA ────────────────────────────────────────────────────
            ['country' => 'FR', 'name' => 'Paris',         'state' => 'Île-de-France',  'lat' => 48.8566,  'lng' => 2.3522,   'radius' => 25000, 'density' => 'major'],
            ['country' => 'GB', 'name' => 'London',        'state' => 'England',        'lat' => 51.5074,  'lng' => -0.1278,  'radius' => 25000, 'density' => 'major'],
            ['country' => 'DE', 'name' => 'Berlin',        'state' => 'Berlin',         'lat' => 52.5200,  'lng' => 13.4050,  'radius' => 20000, 'density' => 'medium'],
        ];

        foreach ($cities as $c) {
            $country = $get($c['country']);
            if (!$country) continue;

            $interval = City::DENSITY_INTERVALS[$c['density']];

            City::firstOrCreate(
                ['country_id' => $country->id, 'name' => $c['name'], 'state' => $c['state']],
                [
                    'latitude'             => $c['lat'],
                    'longitude'            => $c['lng'],
                    'search_radius_meters' => $c['radius'],
                    'community_density'    => $c['density'],
                    'scrape_interval_days' => $interval,
                ]
            );
        }

        $this->command->info('KosherPlacesSeeder: ' . count($cities) . ' ciudades cargadas.');
    }
}
