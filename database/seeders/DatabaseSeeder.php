<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin awal (GANTI password setelah login pertama)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Tahun ajaran aktif
        DB::table('tahun_ajaran')->updateOrInsert(
            ['nama' => '2026/2027'],
            [
                'tanggal_mulai' => '2026-07-13',
                'tanggal_selesai' => '2027-06-26',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Aturan SP (bisa diubah lewat web admin nanti)
        foreach ([[1, 'SP1', 35], [2, 'SP2', 70], [3, 'SP3', 100]] as [$level, $nama, $batas]) {
            DB::table('aturan_sp')->updateOrInsert(
                ['level' => $level],
                ['nama' => $nama, 'batas_poin' => $batas, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Contoh jenis pelanggaran (sesuaikan dengan tata tertib sekolah)
        $contoh = [
            ['Terlambat', 'ringan', 3],
            ['Tidak memakai atribut lengkap', 'ringan', 5],
            ['Tidak mengerjakan tugas', 'ringan', 5],
            ['Keluar kelas tanpa izin', 'sedang', 10],
            ['Membolos', 'sedang', 15],
            ['Merokok', 'berat', 30],
            ['Berkelahi', 'berat', 50],
        ];
        foreach ($contoh as [$nama, $kategori, $poin]) {
            DB::table('jenis_pelanggaran')->updateOrInsert(
                ['nama' => $nama],
                ['kategori' => $kategori, 'poin' => $poin, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->call(JurusanSeeder::class);
    }
}
