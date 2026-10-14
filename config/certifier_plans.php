<?php

// Planes pagos de certificadoras. Precio de lista en USD (CERTIFIER_PLAN_*_USD); el precio en ARS
// se deriva con USD_ARS_RATE, salvo que el .env tenga un CERTIFIER_PLAN_*_PRICE en pesos.
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
    'destacada' => ['label' => 'Destacada'] + $plan('CERTIFIER_PLAN_DESTACADA_PRICE', 'CERTIFIER_PLAN_DESTACADA_USD', 25),
    'pro'       => ['label' => 'Pro'] + $plan('CERTIFIER_PLAN_PRO_PRICE', 'CERTIFIER_PLAN_PRO_USD', 60),
];
