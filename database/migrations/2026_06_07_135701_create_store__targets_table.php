<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Main table for store targets/KPIs
        Schema::create('store_targets', function (Blueprint $table) {
            $table->id();

            // Store identification
            $table->string('store_code', 50)->nullable();
            $table->string('store_name', 255)->nullable();
            $table->string('ownership', 50)->nullable()->default('NON OWNED');
            $table->string('dealer', 100)->nullable();
            $table->string('store_type', 50)->nullable();

            // Geographic information
            $table->string('region', 100)->nullable();
            $table->string('cluster', 100)->nullable();

            // KPI/Business Unit information
            $table->string('kpi', 100)->nullable();
            $table->string('business_unit', 100)->nullable();

            // Budget/Annual Target
            $table->decimal('annual_budget', 15, 2)->default(0);

            // Monthly targets (Jan - Dec 2026) - NO COMMENTS in column definition
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

            // Metadata - use integer instead of year type
            $table->integer('target_year')->default(2026);
            $table->string('source_file', 255)->nullable();
            $table->unsignedBigInteger('upload_batch_id')->nullable();

            // Status flags
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('store_code');
            $table->index('store_name');
            $table->index('region');
            $table->index('cluster');
            $table->index('kpi');
            $table->index('business_unit');
            $table->index(['store_code', 'kpi']);
            $table->index(['region', 'kpi']);
            $table->index(['target_year', 'kpi']);
            $table->index('created_at');
        });

        // Add the generated column AFTER table creation (no DEFAULT allowed)
        DB::statement('ALTER TABLE `store_targets` ADD COLUMN `total_target` DECIMAL(15,2) GENERATED ALWAYS AS (
            COALESCE(target_jan, 0) + COALESCE(target_feb, 0) + COALESCE(target_mar, 0) +
            COALESCE(target_apr, 0) + COALESCE(target_may, 0) + COALESCE(target_jun, 0) +
            COALESCE(target_jul, 0) + COALESCE(target_aug, 0) + COALESCE(target_sep, 0) +
            COALESCE(target_oct, 0) + COALESCE(target_nov, 0) + COALESCE(target_dec, 0)
        ) VIRTUAL');

        // Add comments separately if needed
        try {
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_jan` DECIMAL(15,2) COMMENT 'January 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_feb` DECIMAL(15,2) COMMENT 'February 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_mar` DECIMAL(15,2) COMMENT 'March 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_apr` DECIMAL(15,2) COMMENT 'April 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_may` DECIMAL(15,2) COMMENT 'May 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_jun` DECIMAL(15,2) COMMENT 'June 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_jul` DECIMAL(15,2) COMMENT 'July 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_aug` DECIMAL(15,2) COMMENT 'August 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_sep` DECIMAL(15,2) COMMENT 'September 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_oct` DECIMAL(15,2) COMMENT 'October 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_nov` DECIMAL(15,2) COMMENT 'November 2026 target'");
            DB::statement("ALTER TABLE `store_targets` MODIFY COLUMN `target_dec` DECIMAL(15,2) COMMENT 'December 2026 target'");
        } catch (\Exception $e) {
            // Comments are optional, continue if they fail
        }

        // Store target upload batches tracking
        Schema::create('store_target_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 255);
            $table->string('original_filename', 255);
            $table->string('file_path', 500)->nullable();
            $table->string('file_hash', 64)->nullable();
            $table->integer('total_records')->default(0);
            $table->integer('processed_records')->default(0);
            $table->integer('success_records')->default(0);
            $table->integer('failed_records')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->json('error_log')->nullable();
            $table->json('validation_errors')->nullable();
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('processing_completed_at')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('uploaded_by');
            $table->index('created_at');

            // Foreign key - comment out if users table doesn't exist yet
            // $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });

        // Monthly performance against targets tracking
        Schema::create('store_performance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_target_id');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('store_code', 50);
            $table->string('kpi', 100);
            $table->integer('year');  // Changed from year() to integer()
            $table->unsignedTinyInteger('month');

            // Target vs Actual
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('actual_amount', 15, 2)->default(0);

            // Additional metrics
            $table->string('business_unit', 100)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('store_target_id');
            $table->index(['store_code', 'kpi', 'year', 'month']);
            $table->index(['year', 'month']);

            // Unique constraint to prevent duplicates
            $table->unique(['store_code', 'kpi', 'year', 'month'], 'unique_store_kpi_month');

            // Foreign key
            $table->foreign('store_target_id')->references('id')->on('store_targets')->onDelete('cascade');
        });

        // Add generated columns to store_performance
        DB::statement('ALTER TABLE `store_performance` ADD COLUMN `achievement_percentage` DECIMAL(8,2) GENERATED ALWAYS AS (
            CASE WHEN target_amount > 0 THEN (actual_amount / target_amount) * 100 ELSE 0 END
        ) VIRTUAL');

        DB::statement('ALTER TABLE `store_performance` ADD COLUMN `variance` DECIMAL(15,2) GENERATED ALWAYS AS (
            actual_amount - target_amount
        ) VIRTUAL');

        DB::statement('ALTER TABLE `store_performance` ADD COLUMN `variance_percentage` DECIMAL(8,2) GENERATED ALWAYS AS (
            CASE WHEN target_amount > 0 THEN ((actual_amount - target_amount) / target_amount) * 100 ELSE 0 END
        ) VIRTUAL');

        // Add indexes for generated columns
        DB::statement('ALTER TABLE `store_performance` ADD INDEX `idx_achievement_percentage` (`achievement_percentage`)');
        DB::statement('ALTER TABLE `store_performance` ADD INDEX `idx_variance` (`variance`)');

        // Summary table for quick reporting by region/KPI
        Schema::create('store_target_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('region', 100)->nullable();
            $table->string('cluster', 100)->nullable();
            $table->string('kpi', 100);
            $table->string('business_unit', 100)->nullable();
            $table->integer('year');  // Changed from year() to integer()
            $table->unsignedTinyInteger('month')->nullable();

            // Aggregated targets
            $table->decimal('total_target', 15, 2)->default(0);
            $table->decimal('total_actual', 15, 2)->default(0);
            $table->decimal('achievement_percentage', 8, 2)->nullable();

            // Store count
            $table->integer('store_count')->default(0);
            $table->integer('stores_achieving_target')->default(0);
            $table->integer('stores_exceeding_target')->default(0);
            $table->integer('stores_missing_target')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['region', 'kpi', 'year', 'month']);
            $table->index(['cluster', 'kpi', 'year']);
            $table->index('achievement_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_target_summaries');
        Schema::dropIfExists('store_performance');
        Schema::dropIfExists('store_target_uploads');
        Schema::dropIfExists('store_targets');
    }
};
