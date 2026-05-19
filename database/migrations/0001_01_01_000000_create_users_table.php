<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('mobile_number');
            $table->string('id_number')->unique();
            $table->string('job_title')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->nullable();
            $table->foreignId('store_id')->nullable();
            $table->enum('communication_preference', ['SMS','EMAIL','BOTH']);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};