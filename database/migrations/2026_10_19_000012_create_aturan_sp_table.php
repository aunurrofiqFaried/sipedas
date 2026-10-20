<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_sp', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level')->unique(); // 1, 2, 3
            $table->string('nama', 20);                     // SP1, SP2, SP3
            $table->unsignedSmallInteger('batas_poin');     // 35, 70, 100
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_sp');
    }
};
