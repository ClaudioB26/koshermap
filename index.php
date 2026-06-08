<?php

/**
 * Punto de entrada para hosting compartido (Hostinger).
 * Redirige al index.php real de Laravel en public/
 */
define('LARAVEL_START', microtime(true));

// Ajustar rutas para que Laravel encuentre todo correctamente
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
$_SERVER['DOCUMENT_ROOT']   = __DIR__ . '/public';

require __DIR__ . '/public/index.php';
