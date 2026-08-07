<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certifiers', function (Blueprint $table) {
            // Las certificadoras cargadas manualmente por nosotros ya están "aprobadas".
            // Las que se den de alta desde el sitio entran en 'pending' hasta que las revisemos.
            $table->string('status')->default('approved')->after('address');
            $table->text('rejection_reason')->nullable()->after('status');

            $table->foreignId('owner_id')->nullable()->after('rejection_reason')
                ->constrained('users')->nullOnDelete();

            // Datos para poder investigar la certificadora antes de aprobarla
            $table->string('rabbi_name')->nullable()->after('owner_id');
            $table->year('founded_year')->nullable()->after('rabbi_name');
            $table->text('coverage_description')->nullable()->after('founded_year');
            $table->text('reference_info')->nullable()->after('coverage_description');

            $table->string('submitted_by_name')->nullable()->after('reference_info');
            $table->string('submitted_by_email')->nullable()->after('submitted_by_name');
            $table->string('submitted_by_phone')->nullable()->after('submitted_by_email');
        });
    }

    public function down(): void
    {
        Schema::table('certifiers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
            $table->dropColumn([
                'status', 'rejection_reason', 'rabbi_name', 'founded_year',
                'coverage_description', 'reference_info',
                'submitted_by_name', 'submitted_by_email', 'submitted_by_phone',
            ]);
        });
    }
};
