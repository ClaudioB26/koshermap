<?php

// Precio MENSUAL (ARS) de un banner autoservicio, por ubicación. Los períodos de
// 6 y 12 meses aplican los mismos descuentos que los planes (TierPricingService).
// PLACEHOLDER: valores de arranque; se ajustan por .env.
return [
    'slots' => [
        'top'    => ['price' => (float) env('BANNER_PRICE_TOP', 20000)],
        'bottom' => ['price' => (float) env('BANNER_PRICE_BOTTOM', 10000)],
    ],
];
