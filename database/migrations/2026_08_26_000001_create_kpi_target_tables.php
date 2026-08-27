<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['supervisor_targets', 'company_targets', 'mtn_targets'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                continue;
            }

            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->id();
                if ($tableName === 'supervisor_targets') {
                    $table->string('supervisor_code', 50);
                    $table->string('store_code', 50);
                } elseif ($tableName === 'mtn_targets') {
                    $table->string('mtn_code', 50);
                    $table->string('store_code', 50);
                }
                $table->string('kpi', 100)->nullable();
                $table->decimal('target', 15, 2)->default(0);
                $table->string('month', 20)->default('');
                $table->unsignedBigInteger('upload_batch_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mtn_targets');
        Schema::dropIfExists('company_targets');
        Schema::dropIfExists('supervisor_targets');
    }
};