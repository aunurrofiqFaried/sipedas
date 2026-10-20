<?php

namespace App\Filament\Resources\Mapels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MapelForm
{
    public static function configure(Schema $schema): Schema
    {
    return $schema->components([
        TextInput::make('kode')
            ->maxLength(20)
            ->unique(ignoreRecord: true),

        TextInput::make('nama')
            ->required()
            ->maxLength(255),
    ]);
    }
}
