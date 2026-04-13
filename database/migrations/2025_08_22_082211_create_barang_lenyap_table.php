<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_lenyap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_safety_id')->constrained('pengeluarans_safety')->onDelete('cascade');
            $table->foreignId('barang_safety_id')->constrained('barang_safety')->onDelete('cascade');
            $table->integer('jumlah');
            $table->text('alasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_lenyap');
    }
};