<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->foreignId('customer_id')
                ->nullable()
                ->after('user_id')
                ->constrained('customers')
                ->nullOnDelete();
    
            $table->dropColumn('lokasi');
        });
    
        Schema::table('pengeluarans_gudang', function (Blueprint $table) {
            $table->foreignId('customer_id')
                ->nullable()
                ->after('id')
                ->constrained('customers')
                ->nullOnDelete();
    
            $table->dropColumn('lokasi');
        });
    
        Schema::table('pengeluarans_safety', function (Blueprint $table) {
            $table->foreignId('customer_id')
                ->nullable()
                ->after('user_id')
                ->constrained('customers')
                ->nullOnDelete();
    
            $table->dropColumn('lokasi');
        });
    }
    
    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->string('lokasi');
            $table->dropConstrainedForeignId('customer_id');
        });
    
        Schema::table('pengeluarans_gudang', function (Blueprint $table) {
            $table->string('lokasi');
            $table->dropConstrainedForeignId('customer_id');
        });
    
        Schema::table('pengeluarans_safety', function (Blueprint $table) {
            $table->string('lokasi');
            $table->dropConstrainedForeignId('customer_id');
        });
    }    
};