<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE kosher_places MODIFY place_type ENUM(
            'restaurant','bar','confectionery','bakery','ice_cream','supermarket','temple','school','cemetery','community','other'
        ) NOT NULL DEFAULT 'other'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE kosher_places MODIFY place_type ENUM(
            'restaurant','bar','confectionery','bakery','ice_cream','supermarket','temple','school','cemetery','other'
        ) NOT NULL DEFAULT 'other'");
    }
};
