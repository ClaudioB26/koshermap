<?php

// Marketing por email (Brevo). SOLO se usa desde la notebook: poner BREVO_API_KEY
// en el .env LOCAL, nunca en producción. Ver doc/plan-marketing-brevo.md.
return [
    'brevo_api_key' => env('BREVO_API_KEY'),

    // Remitente de las campañas: tiene que estar verificado en Brevo.
    'sender_name'  => env('MARKETING_SENDER_NAME', 'KosherMap'),
    'sender_email' => env('MARKETING_SENDER_EMAIL', 'info@koshermap.org'),

    // Tope de envíos por día del plan gratuito de Brevo (verificar en Brevo > Plan).
    'daily_limit' => (int) env('MARKETING_DAILY_LIMIT', 300),

    'segments' => [
        'consumers'  => 'Consumidores',
        'businesses' => 'Comercios',
    ],

    'timezone' => 'America/Argentina/Buenos_Aires',
];
