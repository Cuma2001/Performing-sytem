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
        if (Schema::hasTable('mtn_targets')) {
            Schema::table('mtn_targets', function (Blueprint $table) {
                $columns = [
                    'ownership' => fn () => $table->string('ownership')->nullable(),
                    'dealer' => fn () => $table->string('dealer')->nullable(),
                    'store_type' => fn () => $table->string('store_type')->nullable(),
                    'region' => fn () => $table->string('region')->nullable(),
                    'cluster' => fn () => $table->string('cluster')->nullable(),
                    'business_unit' => fn () => $table->string('business_unit')->nullable(),
                    'annual_budget' => fn () => $table->decimal('annual_budget', 15, 2)->default(0),
                    'target_jan' => fn () => $table->decimal('target_jan', 15, 2)->default(0),
                    'target_feb' => fn () => $table->decimal('target_feb', 15, 2)->default(0),
                    'target_mar' => fn () => $table->decimal('target_mar', 15, 2)->default(0),
                    'target_apr' => fn () => $table->decimal('target_apr', 15, 2)->default(0),
                    'target_may' => fn () => $table->decimal('target_may', 15, 2)->default(0),
                    'target_jun' => fn () => $table->decimal('target_jun', 15, 2)->default(0),
                    'target_jul' => fn () => $table->decimal('target_jul', 15, 2)->default(0),
                    'target_aug' => fn () => $table->decimal('target_aug', 15, 2)->default(0),
                    'target_sep' => fn () => $table->decimal('target_sep', 15, 2)->default(0),
                    'target_oct' => fn () => $table->decimal('target_oct', 15, 2)->default(0),
                    'target_nov' => fn () => $table->decimal('target_nov', 15, 2)->default(0),
                    'target_dec' => fn () => $table->decimal('target_dec', 15, 2)->default(0),
                    'target_year' => fn () => $table->integer('target_year')->default(2026),
                    'total_target' => fn () => $table->decimal('total_target', 15, 2)->default(0),
                ];

                foreach ($columns as $name => $definition) {
                    if (!Schema::hasColumn('mtn_targets', $name)) {
                        $definition();
                    }
                }
            });

            return;
        }

        Schema::create('mtn_targets', function (Blueprint $table) {
            $table->id();
            $table->string('mtn_code')->nullable()->index();
            $table->string('store_code')->nullable()->index();
            $table->string('ownership')->nullable();
            $table->string('dealer')->nullable();
            $table->string('store_type')->nullable();
            $table->string('region')->nullable();
            $table->string('cluster')->nullable();
            $table->string('kpi')->nullable()->index();
            $table->string('business_unit')->nullable();
            $table->decimal('annual_budget', 15, 2)->default(0);
            $table->decimal('target', 15, 2)->nullable();
            $table->decimal('target_jan', 15, 2)->default(0);
            $table->decimal('target_feb', 15, 2)->default(0);
            $table->decimal('target_mar', 15, 2)->default(0);
            $table->decimal('target_apr', 15, 2)->default(0);
            $table->decimal('target_may', 15, 2)->default(0);
            $table->decimal('target_jun', 15, 2)->default(0);
            $table->decimal('target_jul', 15, 2)->default(0);
            $table->decimal('target_aug', 15, 2)->default(0);
            $table->decimal('target_sep', 15, 2)->default(0);
            $table->decimal('target_oct', 15, 2)->default(0);
            $table->decimal('target_nov', 15, 2)->default(0);
            $table->decimal('target_dec', 15, 2)->default(0);
            $table->integer('target_year')->default(2026);
            $table->decimal('total_target', 15, 2)->default(0);
            $table->string('month')->nullable();
            $table->bigInteger('upload_batch_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mtn_targets');
    }
};
