<?php

namespace App\Filament\Resources\Jurusans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JurusanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('kode')
                ->required()
                ->maxLength(10)
                ->unique(ignoreRecord: true),

            TextInput::make('nama')
                ->required()
                ->maxLength(255),
        ]);
    }
}
