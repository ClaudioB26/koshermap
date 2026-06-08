<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            // null = nunca sincronizado, fecha = última vez que se envió al servidor
            $table->timestamp('synced_at')->nullable()->after('last_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->dropColumn('synced_at');
        });
    }
};
