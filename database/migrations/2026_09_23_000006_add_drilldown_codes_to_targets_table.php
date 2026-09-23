<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extends the MTN -> Company -> Region -> Store drill-down chain down to the
     * sales-agent targets table via store_code (mirrors store_targets/supervisor_targets).
     */
    public function up(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            if (!Schema::hasColumn('targets', 'store_code')) {
                $table->string('store_code')->nullable()->index()->after('store_id');
            }
            if (!Schema::hasColumn('targets', 'mtn_code')) {
                $table->string('mtn_code')->nullable()->index()->after('store_code');
            }
            if (!Schema::hasColumn('targets', 'region_code')) {
                $table->string('region_code')->nullable()->index()->after('mtn_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            foreach (['store_code', 'mtn_code', 'region_code'] as $column) {
                if (Schema::hasColumn('targets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
