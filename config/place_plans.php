<?php

// Precios de los planes pagos de locales/comercios (ARS por mes).
// PLACEHOLDER: valores de arranque, no son definitivos. Ajustar via env
// segun lo que el usuario defina para su mercado (ver doc/plan-monetizacion.md).
return [
    'destacada_rubro' => [
        'label' => 'Destacado por rubro',
        'price' => (float) env('PLACE_PLAN_DESTACADA_PRICE', 8000),
    ],
    'premium' => [
        'label' => 'Premium (home)',
        'price' => (float) env('PLACE_PLAN_PREMIUM_PRICE', 20000),
    ],
];
