<?php

namespace App\Console\Commands;

use App\Models\Certifier;
use App\Models\KosherPlace;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Corre todos los dias (ver routes/console.php). Por cada certificadora o
 * local con un plan pago proximo a vencer, manda UN aviso por email con el
 * link para renovar -- no genera ningun cobro solo, el dueño elige el
 * periodo y el metodo de pago el mismo cuando entra. Mismo criterio que
 * mayorista-platform (AutoRenewSubscriptions): un aviso por ciclo
 * (tier_reminder_sent_at se limpia en cada renovacion), no uno por dia.
 */
class TierRenewalReminders extends Command
{
    protected $signature = 'tier:renewal-reminders {--days=3 : Dias de anticipacion antes del vencimiento}';

    protected $description = 'Avisa por email a certificadoras/locales con plan pago proximo a vencer.';

    public function handle(): int
    {
        $daysAhead = (int) $this->option('days');
        $threshold = now()->addDays($daysAhead);

        $certifiers = Certifier::where('tier', '!=', Certifier::TIER_FREE)
            ->whereNotNull('tier_expires_at')
            ->where('tier_expires_at', '<=', $threshold)
            ->whereNull('tier_reminder_sent_at')
            ->get();

        foreach ($certifiers as $certifier) {
            $email = $certifier->contact_email ?? $certifier->owner?->email;
            if (! $email) {
                $this->warn("  - Certificadora {$certifier->name}: no se pudo avisar (sin email de contacto).");
                continue;
            }

            $this->notify($email, $certifier->name, route('account.certifiers.plan'), $certifier->tier_expires_at->format('d/m/Y'));
            $certifier->update(['tier_reminder_sent_at' => now()]);
            $this->info("  - Certificadora {$certifier->name}: aviso de vencimiento enviado a {$email}.");
        }

        $places = KosherPlace::where('tier', '!=', KosherPlace::TIER_FREE)
            ->whereNotNull('tier_expires_at')
            ->where('tier_expires_at', '<=', $threshold)
            ->whereNull('tier_reminder_sent_at')
            ->get();

        foreach ($places as $place) {
            $email = $place->owner_email ?? $place->owner?->email;
            if (! $email) {
                $this->warn("  - Local {$place->name}: no se pudo avisar (sin email de contacto).");
                continue;
            }

            $this->notify($email, $place->name, route('account.places.plan', $place), $place->tier_expires_at->format('d/m/Y'));
            $place->update(['tier_reminder_sent_at' => now()]);
            $this->info("  - Local {$place->name}: aviso de vencimiento enviado a {$email}.");
        }

        if ($certifiers->isEmpty() && $places->isEmpty()) {
            $this->info('No hay planes por vencer en los próximos ' . $daysAhead . ' días.');
        }

        return 0;
    }

    private function notify(string $email, string $name, string $renewUrl, string $expiresAt): void
    {
        try {
            Mail::raw(
                "Tu plan pago de \"{$name}\" en KosherMap vence el {$expiresAt}.\n\n"
                . "Para renovarlo entrá a: {$renewUrl}\n\n"
                . "Si no renovás, tu ficha vuelve al plan gratis automáticamente al vencer.",
                fn ($m) => $m->to($email)->subject("Tu plan de \"{$name}\" vence pronto - KosherMap")
            );
        } catch (\Throwable $e) {
            Log::error("Error al enviar aviso de vencimiento de plan ({$name}): " . $e->getMessage());
        }
    }
}
