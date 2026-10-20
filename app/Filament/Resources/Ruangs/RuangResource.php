<?php

namespace App\Filament\Resources\Ruangs;

use App\Filament\Resources\Ruangs\Pages\CreateRuang;
use App\Filament\Resources\Ruangs\Pages\EditRuang;
use App\Filament\Resources\Ruangs\Pages\ListRuangs;
use App\Filament\Resources\Ruangs\Schemas\RuangForm;
use App\Filament\Resources\Ruangs\Tables\RuangsTable;
use App\Models\Ruang;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RuangResource extends Resource
{
    protected static ?string $model = Ruang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'Ruang';
    protected static ?string $pluralModelLabel = 'Ruang';
    protected static ?string $navigationLabel = 'Ruang';
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return RuangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RuangsTable::configure($table);
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
            'index' => ListRuangs::route('/'),
            'create' => CreateRuang::route('/create'),
            'edit' => EditRuang::route('/{record}/edit'),
        ];
    }
}
