<?php

namespace App\Services;

use App\Models\BannerOrder;
use App\Support\BrandedMail;
use Illuminate\Support\Facades\Log;

class BannerOrderService
{
    /** Precio total del período para una ubicación (con descuento por plazo). */
    public static function total(string $slot, string $period): float
    {
        $monthly = (float) config("banner_plans.slots.{$slot}.price", 0);

        return \App\Services\Billing\TierPricingService::priceFor($monthly, $period);
    }

    /**
     * Si el pago está aprobado y la imagen revisada, el banner sale al aire:
     * arranca hoy y dura los meses comprados.
     */
    public function activateIfReady(BannerOrder $order): void
    {
        $order->refresh();

        if ($order->activated_at
            || $order->payment_status !== BannerOrder::APPROVED
            || $order->review_status !== BannerOrder::APPROVED) {
            return;
        }

        $start = now()->startOfDay();
        $end   = $start->copy()->addMonths($order->months)->subDay();

        $order->banner->update([
            'is_active' => true,
            'starts_on' => $start->toDateString(),
            'ends_on'   => $end->toDateString(),
        ]);
        $order->update(['activated_at' => now()]);

        \App\Support\BrandedMail::withLocale($order->user->locale ?: 'es', function () use ($order, $end) {
            $this->mail(
                $order->user->email,
                __('mail.banner_live_subject'),
                __('mail.banner_live_heading'),
                [__('mail.banner_live_body', ['name' => $order->banner->name, 'date' => $end->format('d/m/Y')])],
                [__('mail.banner_live_button'), route('account.banners.index')]
            );
        });
    }

    /** Aviso al admin: hay algo para revisar. */
    public function notifyAdmin(BannerOrder $order, string $what): void
    {
        $this->mail(
            'info@koshermap.org',
            "Banner para revisar: {$order->banner->name}",
            'Pedido de banner para revisar',
            [$what],
            ['Revisar en el panel', route('admin.banner-orders.index')]
        );
    }

    public function mail(string $to, string $subject, string $heading, array $paragraphs, ?array $button = null): void
    {
        try {
            BrandedMail::send($to, $subject, $heading, $paragraphs, [], $button);
        } catch (\Throwable $e) {
            Log::error("BannerOrderService: no se pudo enviar mail a {$to}: " . $e->getMessage());
        }
    }
}
