<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;


class TahunAjaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama')
                ->label('Tahun ajaran')
                ->placeholder('Contoh: 2026/2027')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true),

            DatePicker::make('tanggal_mulai')
                ->label('Tanggal mulai')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y'),

            DatePicker::make('tanggal_selesai')
                ->label('Tanggal selesai')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y')
                ->after('tanggal_mulai'),

            Toggle::make('is_active')
                ->label('Tahun ajaran aktif')
                ->helperText('Hanya satu yang bisa aktif. Mengaktifkan ini otomatis menonaktifkan yang lain. Poin pelanggaran dihitung per tahun ajaran.'),
        ]);
    }
}
