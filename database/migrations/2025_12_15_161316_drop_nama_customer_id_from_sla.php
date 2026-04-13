<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sla', function (Blueprint $table) {
            if (Schema::hasColumn('sla', 'nama_customer_id')) {
                $table->dropForeign(['nama_customer_id']);
                $table->dropColumn('nama_customer_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sla', function (Blueprint $table) {
            $table->foreignId('nama_customer_id')
                ->constrained('master_data')
                ->after('id');
        });
    }
};