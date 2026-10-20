<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Mapel;
use App\Models\Ruang;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * DATA CONTOH UNTUK TESTING SAJA. Hapus sebelum dipakai sekolah.
 * Jalankan ulang kapan saja: jam jadwal otomatis digeser agar "sedang berlangsung".
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $ta = TahunAjaran::aktif();
        $rpl = Jurusan::where('kode', 'RPL')->first();

        $guru = User::updateOrCreate(
            ['username' => 'guru1'],
            ['name' => 'Guru Demo', 'password' => 'guru12345', 'role' => 'guru', 'is_active' => true]
        );

        $ruang = Ruang::firstOrCreate(
            ['nama' => 'Lab RPL 1'],
            ['tipe' => 'lab', 'jurusan_id' => $rpl?->id]
        );

        $kelas = Kelas::firstOrCreate(
            ['tahun_ajaran_id' => $ta->id, 'nama' => 'XI RPL 1'],
            ['tingkat' => 'XI', 'jurusan_id' => $rpl?->id, 'wali_kelas_id' => $guru->id]
        );

        $mapel = Mapel::firstOrCreate(['nama' => 'Pemrograman Web'], ['kode' => 'PWEB']);

        $nama = ['Ahmad Fauzi', 'Budi Santoso', 'Citra Lestari', 'Dewi Anggraini', 'Eko Prasetyo',
                 'Fitri Handayani', 'Gilang Ramadhan', 'Hana Putri', 'Indra Kusuma', 'Joko Susilo'];

        foreach ($nama as $i => $n) {
            $siswa = Siswa::updateOrCreate(
                ['nis' => '2026' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT)],
                ['nama' => $n, 'jenis_kelamin' => $i % 2 === 0 ? 'L' : 'P', 'is_active' => true]
            );

            KelasSiswa::firstOrCreate([
                'tahun_ajaran_id' => $ta->id,
                'kelas_id' => $kelas->id,
                'siswa_id' => $siswa->id,
            ]);
        }

        // Jadwal hari ini: mulai 30 menit lalu, selesai 90 menit lagi
        Jadwal::updateOrCreate(
            [
                'tahun_ajaran_id' => $ta->id,
                'kelas_id' => $kelas->id,
                'mapel_id' => $mapel->id,
                'guru_id' => $guru->id,
                'hari' => now()->dayOfWeekIso,
            ],
            [
                'ruang_id' => $ruang->id,
                'jam_ke_mulai' => 1,
                'jam_ke_selesai' => 4,
                'jam_mulai' => now()->subMinutes(30)->format('H:i:s'),
                'jam_selesai' => now()->addMinutes(90)->format('H:i:s'),
            ]
        );
    }
}
