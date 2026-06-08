<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Comando para generar contenido humano
Artisan::command('human:generate {--limit=50 : Límite de productos a procesar} {--all : Procesar todos los productos}', function () {
    $this->info('Ejecutando comando para generar contenido humano...');
    Artisan::call('human:generate', $this->options());
    $this->info(Artisan::output());
})->purpose('Generate human value layer content for products');

// Comandos de scraping registrados automáticamente desde app/Console/Commands
