<?php

namespace App\Console\Commands;

use App\Services\Marketing\BrevoClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Crea una campaña en Brevo desde un archivo de contenido
 * (resources/marketing/campaigns/<slug>.php), una por idioma, contra la lista
 * "KM <Segmento> <idioma>".
 *
 * Por seguridad NO envía: crea el borrador en Brevo. Hay que pasar --send
 * (o --schedule) para mandarla; --test=mail manda una prueba antes.
 */
class MarketingCampaign extends Command
{
    protected $signature = 'marketing:campaign
                            {slug : Nombre del archivo en resources/marketing/campaigns}
                            {--lang=* : Idiomas (por defecto, todos los del archivo)}
                            {--test= : Mandar una prueba a este mail}
                            {--send : Enviar ahora}
                            {--schedule= : Programar (ej: "2026-11-03 10:00", hora de Argentina)}
                            {--force : Ignorar el tope diario de envíos}';
    protected $description = 'Crea (y opcionalmente envía o programa) una campaña en Brevo (solo notebook).';

    public function handle(): int
    {
        if (config('sync.scraping_enabled') === false) {
            $this->error('marketing:campaign solo puede ejecutarse desde la notebook.');
            return 1;
        }

        $apiKey = config('marketing.brevo_api_key');
        if (! $apiKey) {
            $this->error('Falta BREVO_API_KEY en el .env local.');
            return 1;
        }

        $file = resource_path('marketing/campaigns/' . $this->argument('slug') . '.php');
        if (! File::exists($file)) {
            $this->error("No existe {$file}");
            return 1;
        }

        $campaign = require $file;
        $segment = $campaign['segment'];
        $label = config("marketing.segments.{$segment}");
        if (! $label) {
            $this->error("Segmento desconocido: {$segment}");
            return 1;
        }

        $locales = $this->option('lang') ?: array_keys($campaign['locales']);
        $brevo = new BrevoClient($apiKey);

        $scheduledAt = null;
        if ($this->option('schedule')) {
            $scheduledAt = Carbon::parse($this->option('schedule'), config('marketing.timezone'));
            if ($scheduledAt->isPast()) {
                $this->error('La fecha programada ya pasó.');
                return 1;
            }
        }

        // 1) Preparar: contactos por idioma
        $plan = [];
        $total = 0;
        foreach ($locales as $locale) {
            if (! isset($campaign['locales'][$locale])) {
                $this->warn("  {$locale}: la campaña no tiene texto en este idioma, se omite.");
                continue;
            }
            $listId = $brevo->ensureList("KM {$label} {$locale}");
            $subs = (int) ($brevo->listInfo($listId)['totalSubscribers'] ?? 0);
            $this->line("  {$locale}: lista #{$listId}, {$subs} contactos");
            if ($subs === 0) {
                continue;
            }
            $plan[$locale] = [$listId, $subs];
            $total += $subs;
        }

        if (! $plan) {
            $this->warn('No hay contactos en ninguna lista de este segmento. Corré primero marketing:sync.');
            return 0;
        }

        $sending = $this->option('send') || $scheduledAt;
        $limit = (int) config('marketing.daily_limit');
        if ($sending && $total > $limit && ! $this->option('force')) {
            $this->error("Son {$total} envíos y el tope diario es {$limit}. Dividí por idioma (--lang=) o usá --force.");
            return 1;
        }

        // 2) Crear una campaña por idioma
        $recorded = [];
        foreach ($plan as $locale => [$listId, $subs]) {
            $t = $campaign['locales'][$locale];
            $html = view('emails.campaign', [
                'locale'    => $locale,
                'subject'   => $t['subject'],
                'preheader' => $t['preheader'] ?? '',
                'heading'   => $t['heading'],
                'paragraphs' => $t['paragraphs'],
                'button'    => $t['button'] ?? null,
                'footer'    => $t['footer'] ?? 'KosherMap',
                'unsubscribe' => $t['unsubscribe'] ?? 'Unsubscribe',
            ])->render();

            $payload = [
                'name'       => $this->argument('slug') . " [{$locale}] " . now()->format('Y-m-d H:i'),
                'subject'    => $t['subject'],
                'sender'     => ['name' => config('marketing.sender_name'), 'email' => config('marketing.sender_email')],
                'htmlContent' => $html,
                'recipients' => ['listIds' => [$listId]],
            ];
            if ($scheduledAt) {
                $payload['scheduledAt'] = $scheduledAt->toIso8601String();
            }

            $id = $brevo->createCampaign($payload);
            $this->info("  {$locale}: campaña #{$id} creada ({$subs} destinatarios)");
            $recorded[] = ['slug' => $this->argument('slug'), 'locale' => $locale, 'brevo_id' => $id, 'created_at' => now()->toIso8601String(), 'recipients' => $subs];

            if ($this->option('test')) {
                $brevo->sendTest($id, [$this->option('test')]);
                $this->line("    prueba enviada a {$this->option('test')}");
            }

            if ($this->option('send') && ! $scheduledAt) {
                $brevo->sendNow($id);
                $this->info("    enviada");
            } elseif ($scheduledAt) {
                $this->info("    programada para {$scheduledAt->format('d/m/Y H:i')} ({$scheduledAt->getTimezone()->getName()})");
            }
        }

        $this->recordCampaigns($recorded);

        if (! $sending) {
            $this->warn('Quedó como BORRADOR en Brevo (no se envió). Revisala en Brevo > Campañas y usá --send o --schedule.');
        }

        return 0;
    }

    private function recordCampaigns(array $new): void
    {
        $file = storage_path('app/marketing_campaigns.json');
        $all = File::exists($file) ? (json_decode(File::get($file), true) ?: []) : [];
        File::put($file, json_encode(array_merge($all, $new), JSON_PRETTY_PRINT));
    }
}
