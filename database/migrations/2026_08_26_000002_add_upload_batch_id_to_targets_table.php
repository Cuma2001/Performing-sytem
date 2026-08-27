<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('targets') && ! Schema::hasColumn('targets', 'upload_batch_id')) {
            Schema::table('targets', function (Blueprint $table) {
                $table->unsignedBigInteger('upload_batch_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('targets') && Schema::hasColumn('targets', 'upload_batch_id')) {
            Schema::table('targets', function (Blueprint $table) {
                $table->dropColumn('upload_batch_id');
            });
        }
    }
};