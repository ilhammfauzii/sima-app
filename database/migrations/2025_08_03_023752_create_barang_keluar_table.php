<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_gudang_id')->constrained('pengeluarans_gudang')->onDelete('cascade');
            $table->foreignId('barang_gudang_id')->constrained('barang_gudang')->onDelete('cascade');
            $table->integer('jumlah');
            $table->text('alasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};