<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeCity;
use App\Models\City;
use App\Services\GoogleMapsScraperService;
use Illuminate\Console\Command;

class ScrapeGooglePlaces extends Command
{
    protected $signature = 'scrape:places
                            {--city=      : ID o nombre de la ciudad}
                            {--country=   : Código ISO del país (AR, IL, US...)}
                            {--force      : Ignorar next_scrape_at}
                            {--sync       : Ejecutar directamente (sin cola)}
                            {--one-term   : Solo buscar con el primer término (para pruebas rápidas)}';

    protected $description = 'Scraping de Google Maps para lugares kosher por ciudad.';

    public function handle(): int
    {
        if (config('sync.scraping_enabled') === false) {
            $this->error('El scraping de Google Maps está deshabilitado en este entorno.');
            $this->line('Este proceso solo corre desde el entorno local. Usá sync:push para enviar los datos al servidor.');
            return 1;
        }

        $query = City::with('country')->where('is_active', true);

        if ($cityOption = $this->option('city')) {
            $query->where(function ($q) use ($cityOption) {
                $q->where('id', $cityOption)->orWhere('name', 'like', "%{$cityOption}%");
            });
        }

        if ($countryOption = $this->option('country')) {
            $query->whereHas('country', fn ($q) => $q->where('code', strtoupper($countryOption)));
        }

        if (!$this->option('force')) {
            $query->dueForScraping();
        }

        $cities = $query->orderByRaw("FIELD(community_density,'major','large','medium','small','tiny')")->get();

        if ($cities->isEmpty()) {
            $this->info('No hay ciudades pendientes de scraping.');
            return 0;
        }

        $this->info("Ciudades a procesar: {$cities->count()}");
        $this->newLine();

        foreach ($cities as $city) {
            $label = $city->name . ($city->state ? ", {$city->state}" : '') . " ({$city->country->code})";
            $next  = $city->next_scrape_at?->format('d/m/Y') ?? 'nunca';
            $this->line("  → {$label}  [densidad: {$city->community_density}] [próximo: {$next}]");

            if ($this->option('sync')) {
                // Inyectar callback para ver el output de Node en tiempo real en consola
                $scraper = app(GoogleMapsScraperService::class);
                $scraper->verboseOutput = fn (string $line) => $this->line("  <fg=gray>NODE</> {$line}");

                app()->instance(GoogleMapsScraperService::class, $scraper);
                app()->instance('scrape.one_term', (bool) $this->option('one-term'));

                $job = new ScrapeCity($city, (bool) $this->option('one-term'));
                app()->call([$job, 'handle']);
            } else {
                ScrapeCity::dispatch($city)->onQueue('scraping');
            }
        }

        $this->newLine();
        $modo = $this->option('sync') ? 'ejecutadas' : 'encoladas';
        $this->info("{$cities->count()} ciudades {$modo}.");

        return 0;
    }
}
