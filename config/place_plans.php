<?php

// Planes pagos de locales. Precio de lista en USD (PLACE_PLAN_*_USD); el precio en ARS se deriva
// con USD_ARS_RATE, salvo que el .env tenga un PLACE_PLAN_*_PRICE en pesos.
$rate = (float) env('USD_ARS_RATE', 1500);
$plan = function (string $arsEnv, string $usdEnv, float $usdDefault) use ($rate): array {
    $ars = env($arsEnv);
    if ($ars !== null && $ars !== '') {
        return ['price' => (float) $ars, 'price_usd' => round((float) $ars / $rate, 2)];
    }
    $usd = (float) env($usdEnv, $usdDefault);

    return ['price' => round($usd * $rate, -2), 'price_usd' => $usd];
};
return [
    'destacada_rubro' => ['label' => 'Destacado por rubro'] + $plan('PLACE_PLAN_DESTACADA_PRICE', 'PLACE_PLAN_DESTACADA_USD', 6),
    'premium'         => ['label' => 'Premium (home)'] + $plan('PLACE_PLAN_PREMIUM_PRICE', 'PLACE_PLAN_PREMIUM_USD', 15),
];
