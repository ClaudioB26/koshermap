<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Estado de moderación (reemplaza is_approved booleano)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('rating');
            // IP del visitante para bloqueo por reincidencia
            $table->string('ip_address', 45)->nullable()->after('status');
            // Cuándo aprobó el moderador (el review se muestra 5 min después)
            $table->timestamp('approved_at')->nullable()->after('ip_address');
        });

        // Migrar datos existentes: is_approved=true → approved, false → pending
        DB::statement("UPDATE reviews SET status = CASE WHEN is_approved = 1 THEN 'approved' ELSE 'pending' END");
        // Para los ya aprobados, approved_at = created_at (ya pasaron los 5 min)
        DB::statement("UPDATE reviews SET approved_at = created_at WHERE status = 'approved'");
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['status', 'ip_address', 'approved_at']);
        });
    }
};
