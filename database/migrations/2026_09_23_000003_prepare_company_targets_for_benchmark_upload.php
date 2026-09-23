<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Company KPI sheet uses the same layout as the MTN benchmark sheet:
     * one row per store / KPI / business unit with a budget, twelve monthly
     * targets (202601..202612) and a total, instead of a single target/month pair.
     */
    public function up(): void
    {
        Schema::table('company_targets', function (Blueprint $table) {
            $columns = [
                'store_code' => fn () => $table->string('store_code')->nullable()->index(),
                'store_name' => fn () => $table->string('store_name')->nullable(),
                'ownership' => fn () => $table->string('ownership')->nullable(),
                'dealer' => fn () => $table->string('dealer')->nullable(),
                'store_type' => fn () => $table->string('store_type')->nullable(),
                'region' => fn () => $table->string('region')->nullable(),
                'cluster' => fn () => $table->string('cluster')->nullable(),
                'business_unit' => fn () => $table->string('business_unit')->nullable(),
                'annual_budget' => fn () => $table->decimal('annual_budget', 15, 2)->default(0),
                'target_jan' => fn () => $table->decimal('target_jan', 15, 2)->default(0),
                'target_feb' => fn () => $table->decimal('target_feb', 15, 2)->default(0),
                'target_mar' => fn () => $table->decimal('target_mar', 15, 2)->default(0),
                'target_apr' => fn () => $table->decimal('target_apr', 15, 2)->default(0),
                'target_may' => fn () => $table->decimal('target_may', 15, 2)->default(0),
                'target_jun' => fn () => $table->decimal('target_jun', 15, 2)->default(0),
                'target_jul' => fn () => $table->decimal('target_jul', 15, 2)->default(0),
                'target_aug' => fn () => $table->decimal('target_aug', 15, 2)->default(0),
                'target_sep' => fn () => $table->decimal('target_sep', 15, 2)->default(0),
                'target_oct' => fn () => $table->decimal('target_oct', 15, 2)->default(0),
                'target_nov' => fn () => $table->decimal('target_nov', 15, 2)->default(0),
                'target_dec' => fn () => $table->decimal('target_dec', 15, 2)->default(0),
                'target_year' => fn () => $table->integer('target_year')->default(2026),
                'total_target' => fn () => $table->decimal('total_target', 15, 2)->default(0),
            ];

            foreach ($columns as $name => $definition) {
                if (!Schema::hasColumn('company_targets', $name)) {
                    $definition();
                }
            }

            $table->string('month', 20)->nullable()->change();
            $table->decimal('target', 15, 2)->nullable()->change();
        });

        Schema::table('company_targets', function (Blueprint $table) {
            $table->unique(['store_code', 'kpi', 'business_unit', 'target_year'], 'company_targets_store_kpi_bu_year_unique');
        });
    }

    public function down(): void
    {
        Schema::table('company_targets', function (Blueprint $table) {
            $table->dropUnique('company_targets_store_kpi_bu_year_unique');

            $columns = [
                'store_code', 'store_name', 'ownership', 'dealer', 'store_type', 'region', 'cluster',
                'business_unit', 'annual_budget', 'target_jan', 'target_feb', 'target_mar', 'target_apr',
                'target_may', 'target_jun', 'target_jul', 'target_aug', 'target_sep', 'target_oct',
                'target_nov', 'target_dec', 'target_year', 'total_target',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('company_targets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
