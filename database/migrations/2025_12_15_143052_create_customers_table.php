<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama_customer');
            $table->string('no_telp')->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('npwp', 25)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('id_pln')->nullable();
            $table->foreignId('marketing_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('referensi_reseller')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};