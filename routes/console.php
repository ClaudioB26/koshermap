<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Deshabilitado a propósito (julio 2026): este registro duplicaba el comando
// de app/Console/Commands/GenerateHumanContent.php (y de hecho lo tapaba,
// porque un Artisan::command() con el mismo nombre gana sobre el de clase).
// Generaba reseñas de "expertos" y "comunidad" inventadas, iguales para
// cualquier producto — Google lo detectó como contenido de bajo valor /
// reseñas fabricadas y bloqueó la aprobación de AdSense. Ver el comentario en
// HumanValueLayerService::saveHumanContent() para el detalle completo.
Artisan::command('human:generate {--limit=50} {--all}', function () {
    $this->error('Comando deshabilitado: generaba reseñas falsas detectadas por Google como contenido de bajo valor.');
})->purpose('[Deshabilitado] Generar contenido humano falso para productos');

// Comandos de scraping registrados automáticamente desde app/Console/Commands
