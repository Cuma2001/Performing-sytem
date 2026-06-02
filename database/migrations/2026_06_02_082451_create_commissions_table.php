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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('reconciliation_id')->constrained('reconciliations')->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->integer('week')->nullable();
            $table->enum('period_type', ['weekly', 'monthly', 'quarterly'])->default('monthly');
            $table->decimal('sales_amount', 15, 2);
            $table->decimal('returns_amount', 15, 2)->default(0);
            $table->decimal('net_sales', 15, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->decimal('bonus_amount', 12, 2)->default(0);
            $table->decimal('adjustments', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('net_payable', 15, 2);
            $table->enum('payment_status', ['pending', 'processing', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->json('calculation_breakdown')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'year', 'month', 'period_type'], 'commissions_unique');
            $table->index(['year', 'month', 'payment_status']);
            $table->index('net_payable');
            $table->index('payment_status');
            $table->index(['store_id', 'year', 'month']);
            $table->index(['region_id', 'year', 'month']);
            $table->index('transaction_id');
            $table->index(['payment_status', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
