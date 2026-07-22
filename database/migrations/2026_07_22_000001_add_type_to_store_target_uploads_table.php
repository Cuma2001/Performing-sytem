<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('store_target_uploads', 'type')) {
            Schema::table('store_target_uploads', function (Blueprint $table) {
                $table->string('type')->nullable()->after('file_hash');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('store_target_uploads', 'type')) {
            Schema::table('store_target_uploads', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
