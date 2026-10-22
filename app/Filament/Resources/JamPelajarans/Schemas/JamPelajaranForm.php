<?php

namespace App\Filament\Resources\JamPelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class JamPelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('jam_ke')
                ->label('Jam ke-')
                ->numeric()
                ->minValue(1)
                ->maxValue(15)
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn (string $operation): bool => $operation === 'edit'),

            TimePicker::make('jam_mulai')
                ->label('Mulai')
                ->seconds(false)
                ->required(),

            TimePicker::make('jam_selesai')
                ->label('Selesai')
                ->seconds(false)
                ->required()
                ->after('jam_mulai'),
        ]);
    }
}
