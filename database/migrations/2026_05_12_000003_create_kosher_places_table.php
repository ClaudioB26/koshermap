<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kosher_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();

            $table->string('google_place_id')->unique();

            $table->string('name');

            $table->enum('place_type', [
                'restaurant',
                'bar',
                'confectionery',
                'bakery',
                'temple',
                'school',
                'other',
            ])->default('other');

            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('phone', 50)->nullable();
            $table->string('website')->nullable();

            $table->decimal('google_rating', 3, 1)->nullable();
            $table->unsignedInteger('google_reviews_count')->default(0);
            $table->json('opening_hours')->nullable();
            $table->json('google_types')->nullable();
            $table->string('google_photo_ref')->nullable();

            $table->boolean('is_permanently_closed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_verified_at')->nullable();

            $table->timestamps();

            $table->index(['city_id', 'place_type']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kosher_places');
    }
};
