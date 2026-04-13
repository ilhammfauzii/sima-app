<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('master_id')->constrained('master');
            $table->string('data_master');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_data');
    }
};
