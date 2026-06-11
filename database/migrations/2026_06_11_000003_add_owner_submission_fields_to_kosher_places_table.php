<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->string('source')->default('scraper')->after('google_place_id');

            $table->foreignId('certifier_id')->nullable()->after('place_type')
                  ->constrained()->nullOnDelete();
            $table->string('certifier_other')->nullable()->after('certifier_id');

            $table->string('owner_name')->nullable()->after('website');
            $table->string('owner_email')->nullable()->after('owner_name');
            $table->string('owner_phone')->nullable()->after('owner_email');
        });
    }

    public function down(): void
    {
        Schema::table('kosher_places', function (Blueprint $table) {
            $table->dropConstrainedForeignId('certifier_id');
            $table->dropColumn(['source', 'certifier_other', 'owner_name', 'owner_email', 'owner_phone']);
        });
    }
};
