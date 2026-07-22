<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('region', 255)->nullable()->after('code');
            // Drop foreign key and region_id (optional, but clean up)
            $table->dropForeign(['region_id']);
            $table->dropIndex(['region_id']);
            $table->dropIndex(['region_id', 'is_active']);
            $table->dropColumn('region_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('region');
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->index('region_id');
            $table->index(['region_id', 'is_active']);
        });
    }
};
