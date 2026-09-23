<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The MTN benchmark sheet has one row per store / KPI / business unit with
     * a 2026 budget, twelve monthly targets (202601..202612) and a total.
     * It has no mtn_code or month column, so those become optional.
     */
    public function up(): void
    {
        Schema::table('mtn_targets', function (Blueprint $table) {
            if (! Schema::hasColumn('mtn_targets', 'store_name')) {
                $table->string('store_name')->nullable()->after('store_code');
            }

            $table->string('mtn_code', 50)->nullable()->change();
            $table->string('month', 20)->nullable()->change();
            $table->decimal('target', 15, 2)->nullable()->change();

            $table->unique(['store_code', 'kpi', 'business_unit', 'target_year'], 'mtn_targets_store_kpi_bu_year_unique');
        });
    }

    public function down(): void
    {
        Schema::table('mtn_targets', function (Blueprint $table) {
            $table->dropUnique('mtn_targets_store_kpi_bu_year_unique');

            if (Schema::hasColumn('mtn_targets', 'store_name')) {
                $table->dropColumn('store_name');
            }
        });
    }
};
