<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('banner_id')->constrained()->cascadeOnDelete();
            $table->string('slot', 20);
            $table->unsignedSmallInteger('months');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('ARS');
            $table->string('payment_method', 20);                 // mercadopago | transfer
            $table->string('payment_status', 20)->default('pending'); // pending | approved | rejected
            $table->string('review_status', 20)->default('pending');  // pending | approved | rejected
            $table->string('mp_payment_id')->nullable();
            $table->string('transfer_proof_path')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->index(['payment_status', 'review_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_orders');
    }
};
