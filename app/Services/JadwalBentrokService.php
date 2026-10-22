<?php

namespace App\Services;

use App\Models\Jadwal;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class JadwalBentrokService
{
    /**
     * Cari bentrok pada hari dan rentang waktu yang sama.
     * Dibandingkan dengan jam SEBENARNYA (jam_mulai/jam_selesai), bukan nomor jam ke-berapa.
     *
     * @return array<int, string> daftar pesan bentrok, kosong jika aman
     */
    public function cek(array $data, ?int $ignoreId = null): array
    {
        $jadwalLain = Jadwal::query()
            ->with(['guru:id,name', 'kelas:id,nama', 'ruang:id,nama', 'mapel:id,nama'])
            ->where('tahun_ajaran_id', $data['tahun_ajaran_id'])
            ->where('hari', $data['hari'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->where(function ($q) use ($data) {
                $q->where('guru_id', $data['guru_id'])
                    ->orWhere('kelas_id', $data['kelas_id']);

                if (! empty($data['ruang_id'])) {
                    $q->orWhere('ruang_id', $data['ruang_id']);
                }
            })
            ->get();

        $pesan = [];

        foreach ($jadwalLain as $j) {
            $waktu = substr($j->jam_mulai, 0, 5) . '-' . substr($j->jam_selesai, 0, 5);
            $rincian = "{$j->mapel?->nama} di {$j->kelas?->nama}, {$waktu}";

            if ((int) $j->guru_id === (int) $data['guru_id']) {
                $pesan[] = "Guru {$j->guru?->name} sudah mengajar {$rincian}.";
            }

            if ((int) $j->kelas_id === (int) $data['kelas_id']) {
                $pesan[] = "Kelas {$j->kelas?->nama} sudah punya pelajaran lain ({$j->mapel?->nama}, {$waktu}).";
            }

            if (! empty($data['ruang_id']) && (int) $j->ruang_id === (int) $data['ruang_id']) {
                $pesan[] = "Ruang {$j->ruang?->nama} sudah dipakai {$rincian}.";
            }
        }

        return array_values(array_unique($pesan));
    }

    public function peringatkan(array $pesan): void
    {
        Notification::make()
            ->title('Jadwal bentrok, belum disimpan')
            ->body(new HtmlString(implode('<br>', array_map('e', $pesan))))
            ->danger()
            ->persistent()
            ->send();
    }
}