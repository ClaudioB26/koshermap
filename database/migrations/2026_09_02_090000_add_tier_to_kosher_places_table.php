<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->string('tier')->default('free')->after('status');
            $table->timestamp('tier_expires_at')->nullable()->after('tier');
        });
    }

    public function down(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->dropColumn(['tier', 'tier_expires_at']);
        });
    }
};
