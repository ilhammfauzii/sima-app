<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran_barang_gudang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_gudang_id')->constrained('pengeluarans_gudang')->onDelete('cascade');
            $table->foreignId('barang_gudang_id')->constrained('barang_gudang')->onDelete('cascade');
            $table->integer('jumlah_keluar');
            $table->integer('jumlah_kembali')->nullable();
            $table->integer('jumlah_keluar_gudang')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_barang_gudang');
    }
};