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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_email', 150)->nullable();
            $table->string('user_name', 150)->nullable();

            $table->string('action', 50);
            $table->string('entity_type', 100)->nullable();
            $table->string('table_name', 100)->nullable();
            $table->unsignedBigInteger('record_id')->nullable();

            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->json('changes')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            $table->string('route', 255)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('url', 500)->nullable();

            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();

            $table->integer('response_status')->nullable();
            $table->integer('execution_time_ms')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['table_name', 'record_id']);
            $table->index('action');
            $table->index('created_at');
            $table->index('entity_type');
            $table->index('ip_address');
            $table->index(['action', 'created_at']);
            $table->index(['entity_type', 'record_id']);
        });

        // ✅ FULLTEXT only for MySQL (SAFE)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE audit_logs ADD FULLTEXT audit_fulltext(old_values, new_values)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};