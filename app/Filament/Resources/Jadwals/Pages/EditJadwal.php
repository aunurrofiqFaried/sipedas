<?php

namespace App\Filament\Resources\Jadwals\Pages;

use App\Filament\Resources\Jadwals\JadwalResource;
use App\Services\JadwalBentrokService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\JamPelajaran;

class EditJadwal extends EditRecord
{
    protected static string $resource = JadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = JamPelajaran::isiWaktu($data);

        $service = app(JadwalBentrokService::class);
        $pesan = $service->cek($data, $this->record->id);

        if ($pesan !== []) {
            $service->peringatkan($pesan);
            $this->halt();
        }

        return $data;
    }
}