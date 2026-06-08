<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class GoogleMapsScraperService
{
    private int $pageCount = 0;

    /** @var callable|null */
    public $verboseOutput = null;

    public function searchKosherPlaces(
        float  $lat,
        float  $lng,
        int    $radiusMeters,
        string $cityName,
        string $countryName,
        string $locale = 'es',
        bool   $onlyFirstTerm = false,
    ): array {
        $scriptPath = base_path('scripts/scrape-maps.cjs');

        $args = json_encode([
            'lat'           => $lat,
            'lng'           => $lng,
            'cityName'      => $cityName,
            'countryName'   => $countryName,
            'locale'        => $locale,
            'onlyFirstTerm' => $onlyFirstTerm,
        ]);

        // Pasamos el JSON por stdin para evitar problemas de quoting en Windows
        $process = new Process(
            ['node', $scriptPath],
            base_path(),
            null,
            $args,   // stdin
            3600     // 60 minutos
        );

        $verboseOutput = $this->verboseOutput;

        // Loguear progreso de Node.js en tiempo real (stderr)
        $process->run(function (string $type, string $buffer) use ($cityName, $verboseOutput) {
            if ($type === Process::ERR) {
                foreach (explode("\n", trim($buffer)) as $line) {
                    if (!$line) continue;
                    Log::debug("Node [{$cityName}]: {$line}");
                    if ($verboseOutput) ($verboseOutput)($line);
                }
            }
        });

        if (!$process->isSuccessful()) {
            $stderr = $process->getErrorOutput();
            Log::error('GoogleMapsScraperService falló', ['city' => $cityName, 'stderr' => $stderr]);
            throw new \RuntimeException("Scraper falló para {$cityName}: {$stderr}");
        }

        $stdout = $process->getOutput();
        Log::debug("Node stdout raw ({$cityName}): " . substr($stdout, 0, 200));

        $places = json_decode($stdout, true);

        if (!is_array($places)) {
            Log::error('GoogleMapsScraperService: output inválido', [
                'city'   => $cityName,
                'output' => substr($stdout, 0, 500),
            ]);
            return [];
        }

        $this->pageCount = count($places) + 9;
        Log::info("GoogleMapsScraper {$cityName}: " . count($places) . ' lugares encontrados');

        return $places;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }
}
