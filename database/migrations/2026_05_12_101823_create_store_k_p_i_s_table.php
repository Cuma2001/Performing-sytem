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
        Schema::create('kpis', function (Blueprint $table) {
    $table->id();
    $table->string('type'); // MTN, SMS, STORE
    $table->string('category'); // Sales / General
    $table->string('name');
    $table->decimal('target', 10, 2);
    $table->decimal('weight', 5, 2)->default(1);
    $table->string('store')->nullable();
    $table->date('period');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_k_p_i_s');
    }
};
