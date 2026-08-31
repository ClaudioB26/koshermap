<?php

// Precios de los planes pagos de certificadoras/comercios (ARS por mes).
// PLACEHOLDER: valores de arranque para tener algo funcionando, no son
// definitivos. Ajustar segun lo que el usuario defina para su mercado
// (ver doc/plan-monetizacion.md).
return [
    'destacada' => [
        'label' => 'Destacada',
        'price' => (float) env('CERTIFIER_PLAN_DESTACADA_PRICE', 15000),
    ],
    'pro' => [
        'label' => 'Pro',
        'price' => (float) env('CERTIFIER_PLAN_PRO_PRICE', 40000),
    ],
];
