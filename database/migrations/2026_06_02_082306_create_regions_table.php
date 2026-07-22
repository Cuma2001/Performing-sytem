<?php
// database/migrations/2026_06_26_000000_create_regions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true); // Default to true
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('regions');
    }
};