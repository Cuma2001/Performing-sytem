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
 Schema::create('sales_records', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 100)->unique();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('region_id')->constrained('regions')->restrictOnDelete();
            $table->foreignId('sales_upload_id')->nullable()->constrained('sales_uploads')->nullOnDelete();
            $table->date('sale_date');
            $table->datetime('sale_datetime');
            $table->decimal('amount', 15, 2);
            $table->integer('quantity')->default(1);
            $table->string('product_code', 100)->nullable();
            $table->string('product_name', 200)->nullable();
            $table->string('product_category', 100)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('sku', 100)->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->nullable();
            $table->enum('payment_method', ['cash', 'card', 'mobile_money', 'bank_transfer', 'credit'])->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->string('customer_name', 150)->nullable();
            $table->string('customer_email', 150)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_id', 50)->nullable();
            $table->boolean('is_return')->default(false);
            $table->string('return_reason', 255)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('sale_date');
            $table->index(['sale_date', 'store_id']);
            $table->index(['employee_id', 'sale_date']);
            $table->index('invoice_number');
            $table->index('is_verified');
            $table->index(['region_id', 'store_id']);
            $table->index('product_code');
            $table->index('product_category');
            $table->index('payment_method');
            $table->index(['sale_date', 'is_verified']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_records');
    }
};
