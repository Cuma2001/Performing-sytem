<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MTN benchmarks are stored as monthly columns (target_jan..target_dec),
     * so the single target / month pair is no longer used.
     */
    public function up(): void
    {
        Schema::table('mtn_targets', function (Blueprint $table) {
            foreach (['target', 'month'] as $column) {
                if (Schema::hasColumn('mtn_targets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('mtn_targets', function (Blueprint $table) {
            if (! Schema::hasColumn('mtn_targets', 'target')) {
                $table->decimal('target', 15, 2)->nullable()->after('annual_budget');
            }
            if (! Schema::hasColumn('mtn_targets', 'month')) {
                $table->string('month', 20)->nullable()->after('total_target');
            }
        });
    }
};
