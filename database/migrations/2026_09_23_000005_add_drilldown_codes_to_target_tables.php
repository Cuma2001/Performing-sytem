<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds drill-down reference columns so rows can be traced across the
     * MTN -> Company (dealer) -> Region -> Store benchmark chain:
     * mtn_targets.mtn_code -> company_targets(mtn_code) -> region_targets(mtn_code, region_code) -> store_targets(mtn_code, region_code).
     * These are plain indexed columns, not foreign keys, since the codes repeat
     * across many rows (per KPI / business unit / year) rather than being unique parent keys.
     */
    public function up(): void
    {
        Schema::table('company_targets', function (Blueprint $table) {
            if (!Schema::hasColumn('company_targets', 'mtn_code')) {
                $table->string('mtn_code')->nullable()->index()->after('store_code');
            }
            if (!Schema::hasColumn('company_targets', 'region_code')) {
                $table->string('region_code')->nullable()->index()->after('mtn_code');
            }
        });

        Schema::table('region_targets', function (Blueprint $table) {
            if (!Schema::hasColumn('region_targets', 'mtn_code')) {
                $table->string('mtn_code')->nullable()->index()->after('region_code');
            }
        });

        Schema::table('store_targets', function (Blueprint $table) {
            if (!Schema::hasColumn('store_targets', 'mtn_code')) {
                $table->string('mtn_code')->nullable()->index()->after('store_code');
            }
            if (!Schema::hasColumn('store_targets', 'region_code')) {
                $table->string('region_code')->nullable()->index()->after('mtn_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('store_targets', function (Blueprint $table) {
            foreach (['mtn_code', 'region_code'] as $column) {
                if (Schema::hasColumn('store_targets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('region_targets', function (Blueprint $table) {
            if (Schema::hasColumn('region_targets', 'mtn_code')) {
                $table->dropColumn('mtn_code');
            }
        });

        Schema::table('company_targets', function (Blueprint $table) {
            foreach (['mtn_code', 'region_code'] as $column) {
                if (Schema::hasColumn('company_targets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
