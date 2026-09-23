<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Region KPI sheet mirrors the company benchmark sheet but is keyed by
     * region_code instead of (or alongside) store_code.
     */
    public function up(): void
    {
        Schema::create('region_targets', function (Blueprint $table) {
            $table->id();
            $table->string('region_code')->nullable()->index();
            $table->string('store_code')->nullable()->index();
            $table->string('store_name')->nullable();
            $table->string('ownership')->nullable();
            $table->string('dealer')->nullable();
            $table->string('store_type')->nullable();
            $table->string('region')->nullable();
            $table->string('cluster')->nullable();
            $table->string('kpi')->nullable()->index();
            $table->string('business_unit')->nullable();
            $table->decimal('annual_budget', 15, 2)->default(0);
            $table->decimal('target_jan', 15, 2)->default(0);
            $table->decimal('target_feb', 15, 2)->default(0);
            $table->decimal('target_mar', 15, 2)->default(0);
            $table->decimal('target_apr', 15, 2)->default(0);
            $table->decimal('target_may', 15, 2)->default(0);
            $table->decimal('target_jun', 15, 2)->default(0);
            $table->decimal('target_jul', 15, 2)->default(0);
            $table->decimal('target_aug', 15, 2)->default(0);
            $table->decimal('target_sep', 15, 2)->default(0);
            $table->decimal('target_oct', 15, 2)->default(0);
            $table->decimal('target_nov', 15, 2)->default(0);
            $table->decimal('target_dec', 15, 2)->default(0);
            $table->integer('target_year')->default(2026);
            $table->decimal('total_target', 15, 2)->default(0);
            $table->unsignedBigInteger('upload_batch_id')->nullable()->index();
            $table->timestamps();

            $table->unique(['region_code', 'store_code', 'kpi', 'business_unit', 'target_year'], 'region_targets_region_store_kpi_bu_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('region_targets');
    }
};
