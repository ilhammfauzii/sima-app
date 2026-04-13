// database/migrations/xxxx_xx_xx_xxxxxx_create_master_barang_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_barang_id')->constrained('kategori_barang')->onDelete('cascade');
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->date('tanggal_beli');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_barang');
    }
};