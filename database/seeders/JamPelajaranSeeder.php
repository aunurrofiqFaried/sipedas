<?php

namespace Database\Seeders;

use App\Models\JamPelajaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JamPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        // CONTOH: mulai 07:00, tiap jam 45 menit tanpa istirahat.
        // Koreksi jam sebenarnya sekolahmu lewat halaman admin setelah ini.
        $mulai = Carbon::createFromTimeString('07:00');

        for ($i = 1; $i <= 10; $i++) {
            $selesai = $mulai->copy()->addMinutes(45);

            JamPelajaran::updateOrCreate(
                ['jam_ke' => $i],
                [
                    'jam_mulai' => $mulai->format('H:i:s'),
                    'jam_selesai' => $selesai->format('H:i:s'),
                ]
            );

            $mulai = $selesai;
        }
    }
}