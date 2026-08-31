<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certifiers', function (Blueprint $table) {
            $table->timestamp('tier_expires_at')->nullable()->after('tier');
        });
    }

    public function down(): void
    {
        Schema::table('certifiers', function (Blueprint $table) {
            $table->dropColumn('tier_expires_at');
        });
    }
};
