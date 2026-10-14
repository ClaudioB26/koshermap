<?php

use Database\Seeders\ParveArticleSeoSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Aplica el seeder de SEO de la nota de pareve al correr las migraciones.
 * (Ver Database\Seeders\ParveArticleSeoSeeder; es idempotente.)
 */
return new class extends Migration
{
    public function up(): void
    {
        (new ParveArticleSeoSeeder())->run();
    }

    public function down(): void
    {
        // Sin reversa: el texto anterior sigue en el historial del repositorio.
    }
};
