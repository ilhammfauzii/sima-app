// database/migrations/xxxx_xx_xx_xxxxxx_create_barang_engineer_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_engineer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_barang_id')->constrained('master_barang')->onDelete('cascade');
            $table->integer('stok_sistem_barang');
            $table->integer('stok_fisik_barang');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_engineer');
    }
};