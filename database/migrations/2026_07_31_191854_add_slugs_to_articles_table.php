<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Slugs por idioma (en/pt/fr/ru/he), para tener una URL propia por
            // idioma en vez de reusar el slug en español bajo un prefijo.
            // El español sigue viviendo en la columna 'slug' existente.
            $table->json('slugs')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('slugs');
        });
    }
};
