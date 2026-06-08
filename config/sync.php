<?php

return [
    // URL del servidor de producción (solo se usa desde el local)
    'server_url' => env('SYNC_SERVER_URL', ''),

    // Clave compartida entre local y producción
    'api_key' => env('SYNC_API_KEY', ''),

    // false en producción: bloquea scrape:places y sync:push
    'scraping_enabled' => env('SCRAPING_ENABLED', true),
];
