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
            $table->string('nama');
            $table->string('nomor_pegawai')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('id_roles')->nullable()->constrained('roles')->onDelete('set null');
            $table->string('jabatan')->nullable();
            $table->foreignId('departemen_id')->constrained('master_data')->onDelete('restrict');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users'); 
    }
};