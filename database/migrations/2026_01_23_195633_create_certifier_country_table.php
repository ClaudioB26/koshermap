<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifier_country', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certifier_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['certifier_id', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifier_country');
    }
};
