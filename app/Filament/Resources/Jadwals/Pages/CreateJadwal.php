<?php

namespace App\Filament\Resources\Jadwals\Pages;

use App\Filament\Resources\Jadwals\JadwalResource;
use App\Services\JadwalBentrokService;
use Filament\Resources\Pages\CreateRecord;
use App\Models\JamPelajaran;

class CreateJadwal extends CreateRecord
{
    protected static string $resource = JadwalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = JamPelajaran::isiWaktu($data);

        $service = app(JadwalBentrokService::class);
        $pesan = $service->cek($data);

        if ($pesan !== []) {
            $service->peringatkan($pesan);
            $this->halt();
        }

        return $data;
    }
}