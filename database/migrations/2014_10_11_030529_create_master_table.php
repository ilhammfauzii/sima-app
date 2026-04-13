<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama_master');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master');
    }
};
