<?php

namespace App\Services;

use App\Models\AturanSp;
use App\Models\JenisPelanggaran;
use App\Models\PelanggaranSiswa;
use App\Models\Siswa;
use App\Models\SpSiswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PelanggaranService
{
    /**
     * Catat pelanggaran, simpan SNAPSHOT poin, lalu cek apakah perlu usulan SP.
     *
     * @return array{pelanggaran: PelanggaranSiswa, total_poin: int, sp_baru: ?SpSiswa}
     */
    public function catat(
        User $pencatat,
        Siswa $siswa,
        JenisPelanggaran $jenis,
        TahunAjaran $tahunAjaran,
        string $tanggal,
        ?string $keterangan
    ): array {
        return DB::transaction(function () use ($pencatat, $siswa, $jenis, $tahunAjaran, $tanggal, $keterangan) {
            // kunci baris siswa supaya dua input bersamaan tidak membuat SP ganda
            Siswa::whereKey($siswa->id)->lockForUpdate()->first();

            $pelanggaran = PelanggaranSiswa::create([
                'siswa_id' => $siswa->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jenis_pelanggaran_id' => $jenis->id,
                'poin' => $jenis->poin, // snapshot
                'tanggal' => $tanggal,
                'keterangan' => $keterangan,
                'dicatat_oleh' => $pencatat->id,
            ]);

            $total = $siswa->totalPoin($tahunAjaran->id);
            $spBaru = $this->evaluasiSp($siswa, $tahunAjaran, $total);

            return [
                'pelanggaran' => $pelanggaran,
                'total_poin' => $total,
                'sp_baru' => $spBaru,
            ];
        });
    }

    /**
     * Buat usulan SP (status 'menunggu') untuk level tertinggi yang tercapai,
     * kecuali siswa sudah punya SP di level itu atau lebih tinggi pada tahun ajaran ini.
     */
    public function evaluasiSp(Siswa $siswa, TahunAjaran $tahunAjaran, int $total): ?SpSiswa
    {
        $aturan = AturanSp::where('batas_poin', '<=', $total)
            ->orderByDesc('level')
            ->first();

        if (! $aturan) {
            return null;
        }

        $sudahAda = SpSiswa::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->whereHas('aturan', fn ($q) => $q->where('level', '>=', $aturan->level))
            ->exists();

        if ($sudahAda) {
            return null;
        }

        return SpSiswa::create([
            'siswa_id' => $siswa->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'aturan_sp_id' => $aturan->id,
            'total_poin_saat_terbit' => $total,
            'status' => 'menunggu',
        ]);
    }
}
