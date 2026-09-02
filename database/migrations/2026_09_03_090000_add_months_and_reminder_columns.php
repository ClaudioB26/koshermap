<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certifier_tier_payments', function (Blueprint $table) {
            $table->unsignedTinyInteger('months')->default(1)->after('tier');
        });

        Schema::table('place_tier_payments', function (Blueprint $table) {
            $table->unsignedTinyInteger('months')->default(1)->after('tier');
        });

        Schema::table('certifiers', function (Blueprint $table) {
            $table->timestamp('tier_reminder_sent_at')->nullable()->after('tier_expires_at');
        });

        Schema::table('kosher_places', function (Blueprint $table) {
            $table->timestamp('tier_reminder_sent_at')->nullable()->after('tier_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('certifier_tier_payments', function (Blueprint $table) {
            $table->dropColumn('months');
        });

        Schema::table('place_tier_payments', function (Blueprint $table) {
            $table->dropColumn('months');
        });

        Schema::table('certifiers', function (Blueprint $table) {
            $table->dropColumn('tier_reminder_sent_at');
        });

        Schema::table('kosher_places', function (Blueprint $table) {
            $table->dropColumn('tier_reminder_sent_at');
        });
    }
};
