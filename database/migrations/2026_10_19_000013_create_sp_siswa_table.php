<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sp_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('aturan_sp_id')->constrained('aturan_sp');
            $table->unsignedSmallInteger('total_poin_saat_terbit');
            // sistem membuat status 'menunggu', kesiswaan yang menyetujui
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->date('tanggal_terbit')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // satu level SP hanya sekali per siswa per tahun ajaran
            $table->unique(['siswa_id', 'tahun_ajaran_id', 'aturan_sp_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sp_siswa');
    }
};
