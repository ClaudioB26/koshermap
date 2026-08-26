<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifier_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certifier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('company');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('product_type')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifier_leads');
    }
};
