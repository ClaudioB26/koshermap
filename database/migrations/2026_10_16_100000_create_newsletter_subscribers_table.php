<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('locale', 5)->default('es');
            $table->string('status', 20)->default('pending'); // pending | confirmed | unsubscribed
            $table->string('token', 64)->unique();
            $table->string('consent_ip', 45)->nullable();
            $table->timestamp('consented_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('source', 40)->default('footer');
            $table->timestamps();
        });

        // Opt-out de campañas para cuentas (certificadoras / dueños de locales).
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('marketing_opt_out')->default(false)->after('locale');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('marketing_opt_out');
        });
        Schema::dropIfExists('newsletter_subscribers');
    }
};
