<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('state')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->unsignedInteger('search_radius_meters')->default(15000);

            // Densidad de comunidad judía → determina frecuencia de re-scraping
            $table->enum('community_density', ['tiny', 'small', 'medium', 'large', 'major'])
                  ->default('small');

            // tiny=365d, small=270d, medium=180d, large=90d, major=60d
            $table->unsignedSmallInteger('scrape_interval_days')->default(180);

            $table->timestamp('last_scraped_at')->nullable();
            $table->timestamp('next_scrape_at')->nullable()->index();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['country_id', 'name', 'state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
