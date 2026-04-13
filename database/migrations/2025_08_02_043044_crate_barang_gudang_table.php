<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_gudang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_barang_id')->constrained('master_barang')->onDelete('cascade');
            $table->string('penempatan');
            $table->integer('stok_sistem_barang');
            $table->integer('stok_fisik_barang');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_gudang');
    }
};