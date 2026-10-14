<?php

namespace App\Console\Commands;

use App\Services\Marketing\BrevoClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/**
 * Sincroniza contactos entre producción y Brevo (solo desde la notebook).
 *
 *   1. Trae de producción los contactos con permiso (suscriptos confirmados y
 *      cuentas de certificadoras / dueños de locales).
 *   2. Los carga en listas de Brevo: "KM <Segmento> <idioma>".
 *   3. Da de baja en Brevo a quien se dio de baja en el sitio.
 *   4. Trae de Brevo las bajas (link de baja de una campaña, rebotes, quejas)
 *      y las manda a producción para que nunca vuelvan a recibir nada.
 */
class MarketingSync extends Command
{
    protected $signature = 'marketing:sync {--dry-run : Mostrar qué se haría sin tocar Brevo ni producción}';
    protected $description = 'Sincroniza contactos y bajas entre producción y Brevo (solo notebook).';

    private string $stateFile;

    public function handle(): int
    {
        if (config('sync.scraping_enabled') === false) {
            $this->error('marketing:sync solo puede ejecutarse desde la notebook (SCRAPING_ENABLED=true).');
            return 1;
        }

        $apiKey = config('marketing.brevo_api_key');
        $server = rtrim((string) config('sync.server_url'), '/');
        $syncKey = (string) config('sync.api_key');
        $dry = (bool) $this->option('dry-run');

        if (! $apiKey || ! $server || ! $syncKey) {
            $this->error('Faltan BREVO_API_KEY, SYNC_SERVER_URL o SYNC_API_KEY en el .env local.');
            return 1;
        }

        $this->stateFile = storage_path('app/marketing_state.json');
        $state = File::exists($this->stateFile) ? (json_decode(File::get($this->stateFile), true) ?: []) : [];

        $res = Http::withHeaders(['X-Sync-Key' => $syncKey])->timeout(60)->get($server . '/api/marketing/contacts');
        if (! $res->successful()) {
            $this->error("No se pudieron traer los contactos de producción (HTTP {$res->status()}).");
            return 1;
        }
        $data = $res->json();

        $brevo = new BrevoClient($apiKey);
        $segments = config('marketing.segments');
        $listIds = [];

        foreach ($segments as $segment => $label) {
            $contacts = collect($data[$segment] ?? []);
            $this->info("{$label}: {$contacts->count()} contactos con permiso");

            foreach ($contacts->groupBy(fn ($c) => $c['locale'] ?: 'es') as $locale => $group) {
                $name = "KM {$label} {$locale}";
                $this->line("  - {$name}: {$group->count()}");

                if ($dry) {
                    continue;
                }

                $listIds[$name] ??= $brevo->ensureList($name);

                foreach ($group as $c) {
                    try {
                        $brevo->upsertContact($c['email'], $c['name'] ? strtok($c['name'], ' ') : null, [$listIds[$name]]);
                    } catch (\Throwable $e) {
                        $this->warn("    no se pudo cargar {$c['email']}: " . $e->getMessage());
                    }
                    usleep(120000); // ~8 req/s: dentro del límite de Brevo
                }
            }
        }

        // Bajas hechas en el sitio -> blacklist en Brevo
        $unsubscribed = collect($data['unsubscribed'] ?? []);
        $this->info("Bajas en el sitio a reflejar en Brevo: {$unsubscribed->count()}");
        if (! $dry) {
            foreach ($unsubscribed as $email) {
                try {
                    $brevo->blacklist($email);
                } catch (\Throwable $e) {
                    // Si el contacto nunca estuvo en Brevo, no hay nada que bloquear.
                }
                usleep(120000);
            }
        }

        // Bajas hechas en Brevo -> producción
        $since = $state['unsub_since'] ?? null;
        $fromBrevo = $dry ? [] : $brevo->blacklistedSince($since);
        $this->info('Bajas detectadas en Brevo' . ($since ? " desde {$since}" : '') . ': ' . count($fromBrevo));

        if (! $dry && $fromBrevo) {
            $r = Http::withHeaders(['X-Sync-Key' => $syncKey])->timeout(60)
                ->post($server . '/api/marketing/unsubscribes', ['emails' => $fromBrevo]);
            if ($r->successful()) {
                $this->info('  Aplicadas en producción: ' . json_encode($r->json()));
            } else {
                $this->error("  No se pudieron aplicar en producción (HTTP {$r->status()}).");
                return 1;
            }
        }

        if (! $dry) {
            File::put($this->stateFile, json_encode(['unsub_since' => now()->toIso8601String()]));
        } else {
            $this->warn('[dry-run] No se tocó nada.');
        }

        return 0;
    }
}
