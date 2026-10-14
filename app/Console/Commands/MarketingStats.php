<?php

namespace App\Console\Commands;

use App\Services\Marketing\BrevoClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/** Resultados de las campañas creadas con marketing:campaign (solo notebook). */
class MarketingStats extends Command
{
    protected $signature = 'marketing:stats {--limit=15 : Cantidad de campañas recientes}';
    protected $description = 'Muestra aperturas, clics, bajas y rebotes de las campañas (solo notebook).';

    public function handle(): int
    {
        $apiKey = config('marketing.brevo_api_key');
        if (! $apiKey) {
            $this->error('Falta BREVO_API_KEY en el .env local.');
            return 1;
        }

        $file = storage_path('app/marketing_campaigns.json');
        if (! File::exists($file)) {
            $this->warn('Todavía no se creó ninguna campaña con marketing:campaign.');
            return 0;
        }

        $campaigns = array_slice(array_reverse(json_decode(File::get($file), true) ?: []), 0, (int) $this->option('limit'));
        $brevo = new BrevoClient($apiKey);
        $rows = [];

        foreach ($campaigns as $c) {
            try {
                $info = $brevo->campaign($c['brevo_id']);
            } catch (\Throwable $e) {
                $rows[] = [$c['slug'], $c['locale'], $c['brevo_id'], 'error', '', '', '', '', ''];
                continue;
            }

            $g = $info['statistics']['globalStats'] ?? [];
            $delivered = (int) ($g['delivered'] ?? 0);
            $opens = (int) ($g['uniqueViews'] ?? 0);
            $clicks = (int) ($g['uniqueClicks'] ?? 0);
            $pct = fn ($n) => $delivered > 0 ? round($n / $delivered * 100, 1) . '%' : '-';

            $rows[] = [
                $c['slug'], $c['locale'], $c['brevo_id'], $info['status'] ?? '?',
                (int) ($g['sent'] ?? 0), $delivered,
                $opens . ' (' . $pct($opens) . ')',
                $clicks . ' (' . $pct($clicks) . ')',
                ((int) ($g['unsubscriptions'] ?? 0)) . ' bajas / ' . ((int) ($g['hardBounces'] ?? 0) + (int) ($g['softBounces'] ?? 0)) . ' rebotes',
            ];
        }

        $this->table(['Campaña', 'Idioma', 'ID', 'Estado', 'Enviados', 'Entregados', 'Aperturas', 'Clics', 'Bajas / rebotes'], $rows);

        return 0;
    }
}
