<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('kpi_name');
            $table->string('kpi_category');
            $table->enum('kpi_type', ['MTN','COMPANY','STORE']);
            $table->decimal('weighting', 5, 2);
            $table->decimal('target_value', 10, 2);
            $table->string('financial_period');
            $table->integer('version_number')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};