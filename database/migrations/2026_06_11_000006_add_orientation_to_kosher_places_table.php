<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->enum('orientation', ['orthodox', 'conservative', 'reform', 'other'])
                  ->default('orthodox')
                  ->after('place_type');
        });
    }

    public function down(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->dropColumn('orientation');
        });
    }
};
