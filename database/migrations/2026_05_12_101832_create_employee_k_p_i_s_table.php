<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('kpi_id');
            $table->decimal('target_value', 10, 2);
            $table->decimal('actual_value', 10, 2)->default(0);
            $table->decimal('score', 10, 2)->default(0);
            $table->decimal('weighting', 5, 2);
            $table->string('review_period');
            $table->enum('status', ['PENDING','APPROVED','REJECTED']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_kpis');
    }
};