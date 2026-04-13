<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('pic_id')->constrained('users'); 
            $table->foreignId('returner_id')->nullable()->constrained('users');
            $table->date('tanggal_keluar');
            $table->date('tanggal_kembali')->nullable();
            $table->string('status')->default('dipinjam');
            $table->text('keterangan')->nullable();
            $table->string('lokasi');
            $table->string('bukti_pinjam')->nullable();
            $table->string('bukti_kembali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};