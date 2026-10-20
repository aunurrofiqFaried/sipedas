<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Riwayat siswa di kelas mana pada tahun ajaran apa.
        // Siswa naik kelas tiap tahun, jadi tidak cukup kolom kelas_id di tabel siswa.
        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->timestamps();

            // satu siswa hanya di satu kelas per tahun ajaran
            $table->unique(['tahun_ajaran_id', 'siswa_id']);
            $table->index('kelas_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_siswa');
    }
};
