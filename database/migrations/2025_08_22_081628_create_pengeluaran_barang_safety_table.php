<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran_barang_safety', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_safety_id')->constrained('pengeluarans_safety')->onDelete('cascade');
            $table->foreignId('barang_safety_id')->constrained('barang_safety')->onDelete('cascade');
            $table->integer('jumlah_keluar');
            $table->integer('jumlah_kembali')->nullable();
            $table->integer('jumlah_lenyap')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_barang_safety');
    }
};