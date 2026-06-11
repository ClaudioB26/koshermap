<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('place_owner')->after('email');
            $table->string('google_id')->nullable()->unique()->after('role');
            $table->string('avatar')->nullable()->after('google_id');
            $table->foreignId('certifier_id')->nullable()->after('avatar')
                  ->constrained()->nullOnDelete();
        });

        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NULL');

        // Los usuarios existentes (admins creados antes de este cambio) quedan como admin
        DB::table('users')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('certifier_id');
            $table->dropColumn(['role', 'google_id', 'avatar']);
        });

        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL');
    }
};
