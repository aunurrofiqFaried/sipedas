<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('kontak_wali', function (Blueprint $table) {
        $table->id();
        $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
        $table->foreignId('dihubungi_oleh')->constrained('users');
        $table->string('tujuan', 30); // absensi, pelanggaran, konseling, lainnya
        $table->text('catatan')->nullable();
        $table->timestamps();

        $table->index(['siswa_id', 'created_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('kontak_wali');
}
};
