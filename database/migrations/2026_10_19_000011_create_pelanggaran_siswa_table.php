<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggaran');
            // SNAPSHOT poin saat dicatat, supaya perubahan bobot nanti tidak mengubah data lama
            $table->unsignedSmallInteger('poin');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->timestamps();

            // untuk menghitung total poin per siswa per tahun ajaran
            $table->index(['siswa_id', 'tahun_ajaran_id']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_siswa');
    }
};
