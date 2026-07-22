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
            $table->enum('store_type', ['Franchise', 'Company Owned'])->nullable()->after('is_active');
            $table->foreignId('parent_store_id')->nullable()->constrained('stores')->onDelete('set null')->after('store_type');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null')->after('parent_store_id');
            
            $table->index('parent_store_id');
            $table->index('manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropIndex(['parent_store_id']);
            $table->dropIndex(['manager_id']);
            $table->dropForeign(['parent_store_id']);
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['store_type', 'parent_store_id', 'manager_id']);
        });
    }
};
