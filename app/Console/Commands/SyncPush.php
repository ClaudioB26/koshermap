<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Certifier;
use App\Models\City;
use App\Models\Country;
use App\Models\KosherPlace;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncPush extends Command
{
    protected $signature = 'sync:push
                            {--dry-run : Mostrar qué se enviaría sin enviar nada}
                            {--only= : Enviar solo un paso: countries|certifiers|categories|brands|products|cities|places}';

    protected $description = 'Sincroniza todo el contenido local al servidor de producción.';

    private string $serverUrl;
    private string $apiKey;
    private bool   $dryRun;

    public function handle(): int
    {
        if (config('sync.scraping_enabled') === false) {
            $this->error('sync:push solo puede ejecutarse desde el entorno local.');
            return 1;
        }

        $this->serverUrl = config('sync.server_url');
        $this->apiKey    = config('sync.api_key');
        $this->dryRun    = $this->option('dry-run');
        $only            = $this->option('only');

        if (!$this->serverUrl || !$this->apiKey) {
            $this->error('Faltan SYNC_SERVER_URL o SYNC_API_KEY en el .env');
            return 1;
        }

        $steps = [
            'countries'  => '🌍 Países',
            'certifiers' => '🏅 Certificadoras',
            'categories' => '🏷️  Categorías',
            'brands'     => '🏭 Marcas',
            'products'   => '🛒 Productos',
            'cities'     => '🏙️  Ciudades',
            'places'     => '📍 Lugares aprobados',
        ];

        if ($only && !array_key_exists($only, $steps)) {
            $this->error("Paso inválido: {$only}. Opciones: " . implode(', ', array_keys($steps)));
            return 1;
        }

        $filtered = $only ? [$only => $steps[$only]] : $steps;
        $total    = count($filtered);
        $n        = 1;

        foreach ($filtered as $step => $label) {
            $this->newLine();
            $this->info("── Paso {$n}/{$total}: {$label} " . str_repeat('─', 30));
            $n++;

            $ok = match ($step) {
                'countries'  => $this->syncCountries(),
                'certifiers' => $this->syncCertifiers(),
                'categories' => $this->syncCategories(),
                'brands'     => $this->syncBrands(),
                'products'   => $this->syncProducts(),
                'cities'     => $this->syncCities(),
                'places'     => $this->syncPlaces(),
            };

            if (!$ok) {
                $this->error("Abortado en el paso: {$label}");
                return 1;
            }
        }

        $this->newLine();
        $this->info('✅ Sincronización completa.');
        return 0;
    }

    // ── Países ────────────────────────────────────────────────────

    private function syncCountries(): bool
    {
        $data = Country::all()->map(fn ($c) => [
            'code'   => $c->code,
            'name'   => $c->name,
            'slug'   => $c->slug,
            'locale' => $c->locale,
        ]);

        $this->line("  Países: {$data->count()}");
        if ($this->dryRun) { $this->warn('  [dry-run] No se envió nada.'); return true; }

        $res = $this->post('/api/sync/countries', ['countries' => $data->toArray()]);
        if (!$res) return false;

        $this->info("  ✓ Sincronizados: {$res['synced']}");
        return true;
    }

    // ── Certificadoras ────────────────────────────────────────────

    private function syncCertifiers(): bool
    {
        $data = Certifier::with('countries')->get()->map(fn ($c) => [
            'slug'          => $c->slug,
            'name'          => $c->name,
            'logo_symbol'   => $c->logo_symbol,
            'country_codes' => $c->countries->pluck('code')->toArray(),
        ]);

        $this->line("  Certificadoras: {$data->count()}");
        if ($this->dryRun) { $this->warn('  [dry-run] No se envió nada.'); return true; }

        $res = $this->post('/api/sync/certifiers', ['certifiers' => $data->toArray()]);
        if (!$res) return false;

        $this->info("  ✓ Sincronizadas: {$res['synced']}");
        return true;
    }

    // ── Categorías ────────────────────────────────────────────────

    private function syncCategories(): bool
    {
        // Raíces primero, luego hijos
        $all  = Category::with('parent')->get()->sortBy(fn ($c) => $c->parent_id ? 1 : 0);
        $data = $all->map(fn ($c) => [
            'slug'        => $c->slug,
            'name'        => $c->name,
            'parent_slug' => $c->parent?->slug,
        ]);

        $this->line("  Categorías: {$data->count()}");
        if ($this->dryRun) { $this->warn('  [dry-run] No se envió nada.'); return true; }

        $res = $this->post('/api/sync/categories', ['categories' => $data->values()->toArray()]);
        if (!$res) return false;

        $this->info("  ✓ Sincronizadas: {$res['synced']}");
        return true;
    }

    // ── Marcas ────────────────────────────────────────────────────

    private function syncBrands(): bool
    {
        $total = Brand::count();
        $this->line("  Marcas: {$total}");
        if ($this->dryRun) { $this->warn('  [dry-run] No se envió nada.'); return true; }

        $synced = 0;
        $bar    = $this->output->createProgressBar((int) ceil($total / 200));
        $bar->start();

        Brand::chunk(200, function ($chunk) use (&$synced, $bar) {
            $data = $chunk->map(fn ($b) => ['slug' => $b->slug, 'name' => $b->name]);
            $res  = $this->post('/api/sync/brands', ['brands' => $data->toArray()]);
            if ($res) $synced += $res['synced'] ?? 0;
            $bar->advance();
        });

        $bar->finish();
        $this->newLine();
        $this->info("  ✓ Procesadas: {$synced}");
        return true;
    }

    // ── Productos ─────────────────────────────────────────────────

    private function syncProducts(): bool
    {
        $total = Product::count();
        $this->line("  Productos: {$total} (chunks de 100)");
        if ($this->dryRun) { $this->warn('  [dry-run] No se envió nada.'); return true; }

        $created = $updated = $skipped = 0;
        $bar     = $this->output->createProgressBar((int) ceil($total / 100));
        $bar->start();

        Product::with(['brand', 'certifier', 'category', 'countries'])
            ->chunk(100, function ($chunk) use (&$created, &$updated, &$skipped, $bar) {
                $data = $chunk->map(fn ($p) => [
                    'slug'           => $p->slug,
                    'name'           => $p->name,
                    'barcode'        => $p->barcode,
                    'image_url'      => $p->image_url,
                    'kosher_status'  => $p->kosher_status,
                    'source'         => $p->source,
                    'description'    => $p->description,
                    'brand_slug'     => $p->brand?->slug,
                    'certifier_slug' => $p->certifier?->slug,
                    'category_slug'  => $p->category?->slug,
                    'country_codes'  => $p->countries->pluck('code')->toArray(),
                ]);

                $res = $this->post('/api/sync/products', ['products' => $data->toArray()], timeout: 60);
                if ($res) {
                    $created += $res['created'] ?? 0;
                    $updated += $res['updated'] ?? 0;
                    $skipped += $res['skipped'] ?? 0;
                }
                $bar->advance();
            });

        $bar->finish();
        $this->newLine();
        $this->info("  ✓ Creados: {$created}  Actualizados: {$updated}  Saltados: {$skipped}");
        return true;
    }

    // ── Ciudades ──────────────────────────────────────────────────

    private function syncCities(): bool
    {
        $data = City::with('country')->get()->map(fn ($c) => [
            'name'                 => $c->name,
            'state'                => $c->state,
            'country_code'         => $c->country->code,
            'latitude'             => $c->latitude,
            'longitude'            => $c->longitude,
            'search_radius_meters' => $c->search_radius_meters,
            'community_density'    => $c->community_density,
            'is_active'            => $c->is_active,
        ]);

        $this->line("  Ciudades: {$data->count()}");
        if ($this->dryRun) { $this->warn('  [dry-run] No se envió nada.'); return true; }

        $res = $this->post('/api/sync/cities', ['cities' => $data->toArray()]);
        if (!$res) return false;

        $this->info("  ✓ Sincronizadas: {$res['synced']}");
        if (!empty($res['missing_countries'])) {
            $this->warn('  ⚠ Países faltantes en prod: ' . implode(', ', $res['missing_countries']));
        }
        return true;
    }

    // ── Lugares ───────────────────────────────────────────────────

    private function syncPlaces(): bool
    {
        $query  = KosherPlace::pendingSync()->where('is_active', true)->with('city.country');
        $count  = $query->count();

        if ($count === 0) {
            $this->info('  ✓ Nada que sincronizar — todo al día.');
            return true;
        }

        $models = $query->get();
        $places = $models->map(fn ($p) => [
            'google_place_id'       => $p->google_place_id,
            'status'                => $p->status,
            'city_slug'             => $p->city->name . '__' . $p->city->country->code,
            'name'                  => $p->name,
            'place_type'            => $p->place_type,
            'address'               => $p->address,
            'latitude'              => $p->latitude,
            'longitude'             => $p->longitude,
            'phone'                 => $p->phone,
            'website'               => $p->website,
            'google_rating'         => $p->google_rating,
            'google_reviews_count'  => $p->google_reviews_count,
            'opening_hours'         => $p->opening_hours,
            'google_types'          => $p->google_types,
            'google_photo_ref'      => $p->google_photo_ref,
            'is_permanently_closed' => $p->is_permanently_closed,
            'is_active'             => $p->is_active,
            'last_verified_at'      => $p->last_verified_at?->toISOString(),
        ]);

        $this->line("  Lugares pendientes: {$places->count()}");

        if ($this->dryRun) {
            $this->table(
                ['google_place_id', 'nombre', 'ciudad'],
                $places->map(fn ($p) => [$p['google_place_id'], $p['name'], $p['city_slug']])->toArray()
            );
            $this->warn('  [dry-run] No se envió nada.');
            return true;
        }

        $res = $this->post('/api/sync/places', ['places' => $places->toArray()], timeout: 60);
        if (!$res) return false;

        $this->info("  ✓ Creados: {$res['created']}  Actualizados: {$res['updated']}  Rechazados: {$res['rejected']}");
        $models->each(fn ($p) => $p->update(['synced_at' => now()]));
        return true;
    }

    // ── HTTP helper ───────────────────────────────────────────────

    private function post(string $path, array $body, int $timeout = 30): ?array
    {
        $response = Http::withHeaders(['X-Sync-Key' => $this->apiKey])
            ->timeout($timeout)
            ->post($this->serverUrl . $path, $body);

        if ($response->successful()) {
            return $response->json();
        }

        $this->error("  HTTP {$response->status()} en {$path}");
        $this->error('  ' . substr($response->body(), 0, 300));
        return null;
    }
}
