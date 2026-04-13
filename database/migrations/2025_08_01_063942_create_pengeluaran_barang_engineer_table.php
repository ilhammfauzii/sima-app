<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran_barang_engineer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_id')->constrained('pengeluarans')->onDelete('cascade');
            $table->foreignId('barang_engineer_id')->constrained('barang_engineer')->onDelete('cascade');
            $table->integer('jumlah_keluar');
            $table->integer('jumlah_baik')->nullable();
            $table->integer('jumlah_hangus')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_barang_engineer');
    }
};