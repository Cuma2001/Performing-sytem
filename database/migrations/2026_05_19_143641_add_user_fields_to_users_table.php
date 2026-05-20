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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'id_no')) {
                $table->string('id_no')->nullable()->unique();
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->unique();
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable();
            }

            if (!Schema::hasColumn('users', 'store')) {
                $table->string('store')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id_no')) {
                $table->dropUnique(['id_no']);
                $table->dropColumn('id_no');
            }

            if (Schema::hasColumn('users', 'phone')) {
                $table->dropUnique(['phone']);
                $table->dropColumn('phone');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'store')) {
                $table->dropColumn('store');
            }
        });
    }
};
