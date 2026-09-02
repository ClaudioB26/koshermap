<?php

namespace App\Console\Commands;

use App\Models\Certifier;
use App\Models\CertifierTierPayment;
use App\Models\KosherPlace;
use App\Models\PlaceTierPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Corre todos los dias (ver routes/console.php). Baja a "free" las
 * certificadoras/locales cuyo plan pago vencio y nadie renovo, dando unos
 * dias de gracia por si el pago (sobre todo transferencia, que se revisa a
 * mano) todavia esta en curso -- mismo criterio que mayorista-platform
 * (AutoSuspendSubscriptions), sin dejar el plan vencido colgado para
 * siempre esperando que alguien lo note a mano en el admin.
 */
class TierExpireOverdue extends Command
{
    protected $signature = 'tier:expire-overdue {--grace-days=5 : Dias de gracia despues del vencimiento antes de bajar a gratis}';

    protected $description = 'Baja a plan gratis las certificadoras/locales cuyo plan pago vencio sin renovar.';

    public function handle(): int
    {
        $graceDays = (int) $this->option('grace-days');
        $cutoff = now()->subDays($graceDays);

        $certifiers = Certifier::where('tier', '!=', Certifier::TIER_FREE)
            ->whereNotNull('tier_expires_at')
            ->where('tier_expires_at', '<=', $cutoff)
            ->get();

        foreach ($certifiers as $certifier) {
            // Si ya subio un comprobante de transferencia que sigue pendiente
            // de revision, le damos la chance de que se confirme antes de bajarlo.
            $tienePagoEnCurso = CertifierTierPayment::where('certifier_id', $certifier->id)
                ->where('status', 'pending')->exists();

            if ($tienePagoEnCurso) {
                $this->line("  - Certificadora {$certifier->name}: tiene un comprobante en revision, no se baja todavia.");
                continue;
            }

            $certifier->update(['tier' => Certifier::TIER_FREE, 'tier_expires_at' => null, 'tier_reminder_sent_at' => null]);

            $email = $certifier->contact_email ?? $certifier->owner?->email;
            if ($email) {
                $this->notify($email, $certifier->name, route('account.certifiers.plan'));
            }
            $this->info("  - Certificadora {$certifier->name}: plan vencido, bajado a gratis.");
        }

        $places = KosherPlace::where('tier', '!=', KosherPlace::TIER_FREE)
            ->whereNotNull('tier_expires_at')
            ->where('tier_expires_at', '<=', $cutoff)
            ->get();

        foreach ($places as $place) {
            $tienePagoEnCurso = PlaceTierPayment::where('place_id', $place->id)
                ->where('status', 'pending')->exists();

            if ($tienePagoEnCurso) {
                $this->line("  - Local {$place->name}: tiene un comprobante en revision, no se baja todavia.");
                continue;
            }

            $place->update(['tier' => KosherPlace::TIER_FREE, 'tier_expires_at' => null, 'tier_reminder_sent_at' => null]);

            $email = $place->owner_email ?? $place->owner?->email;
            if ($email) {
                $this->notify($email, $place->name, route('account.places.plan', $place));
            }
            $this->info("  - Local {$place->name}: plan vencido, bajado a gratis.");
        }

        if ($certifiers->isEmpty() && $places->isEmpty()) {
            $this->info('No hay planes vencidos para bajar.');
        }

        return 0;
    }

    private function notify(string $email, string $name, string $renewUrl): void
    {
        try {
            Mail::raw(
                "El plan pago de \"{$name}\" en KosherMap venció y volvió al plan gratis.\n\n"
                . "Si querés renovarlo, entrá a: {$renewUrl}",
                fn ($m) => $m->to($email)->subject("Tu plan de \"{$name}\" venció - KosherMap")
            );
        } catch (\Throwable $e) {
            Log::error("Error al enviar aviso de plan vencido ({$name}): " . $e->getMessage());
        }
    }
}
