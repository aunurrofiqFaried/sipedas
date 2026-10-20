<?php

namespace App\Filament\Resources\Mapels;

use App\Filament\Resources\Mapels\Pages\CreateMapel;
use App\Filament\Resources\Mapels\Pages\EditMapel;
use App\Filament\Resources\Mapels\Pages\ListMapels;
use App\Filament\Resources\Mapels\Schemas\MapelForm;
use App\Filament\Resources\Mapels\Tables\MapelsTable;
use App\Models\Mapel;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MapelResource extends Resource
{
    protected static ?string $model = Mapel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'Mata Pelajaran';
    protected static ?string $pluralModelLabel = 'Mata Pelajaran';
    protected static ?string $navigationLabel = 'Mata Pelajaran';
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return MapelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MapelsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMapels::route('/'),
            'create' => CreateMapel::route('/create'),
            'edit' => EditMapel::route('/{record}/edit'),
        ];
    }
}
