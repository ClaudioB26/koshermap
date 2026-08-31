<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifier_tier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certifier_id')->constrained()->cascadeOnDelete();
            $table->string('tier'); // destacada | pro
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('ARS');
            $table->string('payment_method'); // mercadopago | transfer
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->string('mp_payment_id')->nullable();
            $table->string('transfer_proof_path')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifier_tier_payments');
    }
};
