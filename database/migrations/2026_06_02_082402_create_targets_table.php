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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->decimal('sales_target', 15, 2);
            $table->decimal('quantity_target', 10, 0)->nullable();
            $table->decimal('revenue_target', 15, 2)->nullable();
            $table->decimal('customer_target', 10, 0)->nullable();
            $table->enum('target_type', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->integer('quarter')->nullable();
            $table->enum('status', ['pending', 'achieved', 'exceeded', 'missed'])->default('pending');
            $table->decimal('achievement_percentage', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique(['employee_id', 'year', 'month', 'target_type'], 'targets_unique');
            $table->index(['year', 'month']);
            $table->index('status');
            $table->index('target_type');
            $table->index(['region_id', 'store_id']);
            $table->index('achievement_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
