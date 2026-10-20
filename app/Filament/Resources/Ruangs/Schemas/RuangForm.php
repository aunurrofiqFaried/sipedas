<?php

namespace App\Filament\Resources\Ruangs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RuangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama')
                ->label('Nama ruang')
                ->placeholder('Contoh: Lab RPL 1')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),

            Select::make('tipe')
                ->options([
                    'kelas' => 'Ruang Kelas',
                    'lab' => 'Laboratorium',
                ])
                ->required(),

            Select::make('jurusan_id')
                ->label('Jurusan')
                ->relationship('jurusan', 'nama')
                ->searchable()
                ->preload()
                ->placeholder('Umum (tidak khusus jurusan)'),

            TextInput::make('kapasitas')
                ->numeric()
                ->minValue(1)
                ->maxValue(500),
        ]);
    }
}