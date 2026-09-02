<?php

namespace App\Services\Billing;

/**
 * Calcula el precio de un plan (destacada/pro para certificadoras,
 * destacada_rubro/premium para locales) segun el periodo elegido. El precio
 * base en config/certifier_plans.php y config/place_plans.php siempre es
 * MENSUAL -- este servicio aplica la cantidad de meses y el descuento por
 * compromiso, mismo criterio que mayorista-platform (PricingService).
 */
class TierPricingService
{
    public const PERIOD_MONTHS = [
        '1'  => 1,
        '6'  => 6,
        '12' => 12,
    ];

    public const PERIOD_DISCOUNT_PERCENT = [
        '1'  => 0,
        '6'  => 15,
        '12' => 25,
    ];

    public const PERIOD_LABELS = [
        '1'  => '1 mes',
        '6'  => '6 meses (15% off)',
        '12' => '12 meses (25% off)',
    ];

    /**
     * Precio total a cobrar por el periodo elegido, ya con el descuento
     * aplicado (no es el precio mensual, es el total del periodo completo).
     */
    public static function priceFor(float $monthlyPrice, string $period): float
    {
        $months = self::PERIOD_MONTHS[$period] ?? 1;
        $discountPercent = self::PERIOD_DISCOUNT_PERCENT[$period] ?? 0;

        $listPrice = $monthlyPrice * $months;

        return round($listPrice * (1 - $discountPercent / 100), 2);
    }

    public static function monthsFor(string $period): int
    {
        return self::PERIOD_MONTHS[$period] ?? 1;
    }

    /**
     * Proxima fecha de vencimiento al renovar/activar un plan. Si todavia
     * quedaba tiempo vigente, se suma desde ahi (no se pierde lo que ya
     * estaba pago); si ya vencio o es la primera vez, se cuenta desde hoy.
     */
    public static function nextExpiry(?\Illuminate\Support\Carbon $currentExpiresAt, int $months): \Illuminate\Support\Carbon
    {
        $base = ($currentExpiresAt && $currentExpiresAt->isFuture()) ? $currentExpiresAt : now();

        return $base->copy()->addMonths($months);
    }
}
