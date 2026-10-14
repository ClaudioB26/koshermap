<?php

// Cotización usada para convertir los precios de lista en dólares a pesos.
// Mercado Pago cobra en pesos; las transferencias pueden hacerse en pesos o en dólares.
// Actualizar USD_ARS_RATE en el .env cuando cambie la cotización de referencia
// (después, php artisan config:clear).
return [
    'usd_ars_rate' => (float) env('USD_ARS_RATE', 1500),
];
