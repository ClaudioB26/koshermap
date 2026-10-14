<?php

// Banner autoservicio: precio MENSUAL por ubicación. Lista en USD (BANNER_PRICE_*_USD); el ARS se
// deriva con USD_ARS_RATE, salvo que el .env tenga BANNER_PRICE_TOP / BANNER_PRICE_BOTTOM en pesos.
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
    'slots' => [
        'top'    => $plan('BANNER_PRICE_TOP', 'BANNER_PRICE_TOP_USD', 15),
        'bottom' => $plan('BANNER_PRICE_BOTTOM', 'BANNER_PRICE_BOTTOM_USD', 8),
    ],
];
