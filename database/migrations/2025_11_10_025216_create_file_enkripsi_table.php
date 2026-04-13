<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_enkripsi', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['rahasia', 'tidak_rahasia'])->default('tidak_rahasia');
            $table->enum('status', ['aktif', 'terhapus'])->default('aktif');
            $table->string('jenis_dokumen');
            $table->string('nama_file_asli');
            $table->string('path_file_terenkripsi');
            $table->text('kunci_enkripsi')->nullable();
            $table->string('ukuran_file');
            $table->string('tipe_file');
            $table->json('penerima');
            $table->foreignId('diupload_oleh')->constrained('users');
            $table->text('deskripsi')->nullable();
            $table->timestamp('kadaluarsa_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_enkripsi');
    }
};