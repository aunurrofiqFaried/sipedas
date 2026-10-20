<?php

namespace App\Services;

use App\Models\Jadwal;
use Illuminate\Support\Carbon;

class JadwalService
{
    // Toleransi pengisian jurnal setelah jam pelajaran selesai (menit).
    // 0 = hanya boleh diisi tepat selama jam mengajar.
    public const TOLERANSI_MENIT = 0;

    // Apakah SEKARANG (jam server) berada di dalam jam mengajar jadwal ini?
    public static function dalamJamMengajar(Jadwal $jadwal, ?Carbon $now = null): bool
    {
        $now ??= now();

        if ((int) $jadwal->hari !== $now->dayOfWeekIso) {
            return false;
        }

        $mulai = $now->copy()->setTimeFromTimeString($jadwal->jam_mulai);
        $selesai = $now->copy()->setTimeFromTimeString($jadwal->jam_selesai)
            ->addMinutes(self::TOLERANSI_MENIT);

        return $now->gte($mulai) && $now->lte($selesai);
    }
}
