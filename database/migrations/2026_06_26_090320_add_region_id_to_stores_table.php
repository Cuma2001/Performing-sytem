<?php
// database/migrations/2026_06_26_000005_add_region_id_to_stores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('stores', 'region_id')) {
                $table->unsignedBigInteger('region_id')->nullable()->after('id');
                $table->foreign('region_id')
                      ->references('id')
                      ->on('regions')
                      ->onDelete('set null');
                $table->index('region_id');
            }
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'region_id')) {
                $table->dropForeign(['region_id']);
                $table->dropColumn('region_id');
            }
        });
    }
};