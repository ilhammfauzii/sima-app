<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('nama_customer_id')->constrained('master_data');
            $table->foreignId('departemen_id')->constrained('master_data');
            $table->foreignId('service_type_id')->constrained('master_data');
            $table->foreignId('PIC_id')->constrained('users');
            $table->text('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('deadline')->nullable();
            $table->date('start')->nullable();
            $table->date('finish')->nullable();
            $table->string('status')->default('ONGOING');
            $table->string('file')->nullable();
            $table->string('problem')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla');
    }
};