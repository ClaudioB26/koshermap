<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('place_scraping_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();

            $table->enum('status', ['pending', 'running', 'completed', 'failed'])
                  ->default('pending');

            $table->unsignedSmallInteger('places_found')->default(0);
            $table->unsignedSmallInteger('places_created')->default(0);
            $table->unsignedSmallInteger('places_updated')->default(0);
            $table->unsignedSmallInteger('places_closed')->default(0);
            $table->unsignedSmallInteger('api_requests_made')->default(0);

            $table->text('error_message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['city_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('place_scraping_logs');
    }
};
