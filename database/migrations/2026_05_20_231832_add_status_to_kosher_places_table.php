<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('google_place_id');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'rejection_reason']);
        });
    }
};
