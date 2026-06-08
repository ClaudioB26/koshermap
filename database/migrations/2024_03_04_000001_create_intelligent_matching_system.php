<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla principal de mapeo permanente OU → OFF
        Schema::create('ou_off_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('ou_product_name')->index();
            $table->string('ou_brand_name')->index();
            $table->string('off_product_name')->nullable();
            $table->string('off_brand_name')->nullable();
            $table->string('off_barcode')->nullable()->unique();
            $table->string('off_image_url')->nullable();
            $table->integer('confidence_score')->default(0); // 0-100
            $table->enum('match_status', ['auto_matched', 'manual_verified', 'pending_review', 'rejected'])->default('auto_matched');
            $table->json('scoring_breakdown')->nullable(); // Detalle de cómo se calculó el score
            $table->string('matched_by')->nullable(); // 'system' o ID de usuario
            $table->timestamps();
            
            $table->index(['ou_product_name', 'ou_brand_name']);
            $table->index('confidence_score');
            $table->index('match_status');
        });

        // Tabla de búsquedas fallidas para curación humana
        Schema::create('failed_matches', function (Blueprint $table) {
            $table->id();
            $table->string('ou_product_name');
            $table->string('ou_brand_name');
            $table->string('search_term_used');
            $table->json('off_candidates')->nullable(); // Top 3-5 candidatos con sus scores
            $table->integer('best_score')->default(0);
            $table->text('rejection_reason')->nullable();
            $table->boolean('needs_human_review')->default(true);
            $table->timestamp('reviewed_at')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->timestamps();
            
            $table->index(['ou_product_name', 'ou_brand_name']);
            $table->index('needs_human_review');
        });

        // Catálogo de normalizaciones (reutilizable)
        Schema::create('brand_normalizations', function (Blueprint $table) {
            $table->id();
            $table->string('original_brand');
            $table->string('normalized_brand');
            $table->string('common_variation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('original_brand');
            $table->index('normalized_brand');
        });

        // Cache de búsquedas exitosas por marca
        Schema::create('brand_search_cache', function (Blueprint $table) {
            $table->id();
            $table->string('search_brand');
            $table->string('matched_off_brand');
            $table->integer('success_count')->default(1);
            $table->timestamp('last_success');
            $table->float('average_confidence')->default(0);
            $table->timestamps();
            
            $table->unique('search_brand');
            $table->index('matched_off_brand');
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_search_cache');
        Schema::dropIfExists('brand_normalizations');
        Schema::dropIfExists('failed_matches');
        Schema::dropIfExists('ou_off_mappings');
    }
};
