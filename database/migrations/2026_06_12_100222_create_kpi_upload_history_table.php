<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kpi_upload_history', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('type'); // store, supervisor, company, mtn
            $table->integer('records')->default(0);
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kpi_upload_history');
    }
};