<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Requiere el cron de siempre en el servidor: * * * * * php artisan schedule:run
// (mismo criterio que mayorista-platform, ver su routes/console.php).
// La salida de cada corrida se guarda en storage/logs/tier-scheduler.log (bloqueado
// por web en el .htaccess): sin eso el scheduler no deja ningun rastro y no hay forma
// de saber si el cron del servidor esta funcionando.
Schedule::command('tier:renewal-reminders')->daily()
    ->appendOutputTo(storage_path('logs/tier-scheduler.log'));
Schedule::command('tier:expire-overdue')->daily()
    ->appendOutputTo(storage_path('logs/tier-scheduler.log'));

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
