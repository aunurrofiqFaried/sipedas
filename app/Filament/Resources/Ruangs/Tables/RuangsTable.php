<?php

namespace App\Filament\Resources\Ruangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class RuangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'lab' ? 'Laboratorium' : 'Ruang Kelas'),

                TextColumn::make('jurusan.nama')
                    ->label('Jurusan')
                    ->placeholder('Umum'),

                TextColumn::make('kapasitas')
                    ->numeric(),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->options([
                        'kelas' => 'Ruang Kelas',
                        'lab' => 'Laboratorium',
                    ]),

                SelectFilter::make('jurusan_id')
                    ->label('Jurusan')
                    ->relationship('jurusan', 'nama'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
