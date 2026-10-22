<?php

namespace App\Filament\Resources\Kelas\Tables;

use App\Models\TahunAjaran;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('nama')
                ->label('Kelas')
                ->searchable()
                ->sortable(),

            TextColumn::make('tahunAjaran.nama')
                ->label('Tahun ajaran')
                ->sortable(),

            TextColumn::make('jurusan.nama')
                ->label('Jurusan'),

            TextColumn::make('waliKelas.name')
                ->label('Wali kelas')
                ->placeholder('Belum diatur'),

            TextColumn::make('siswa_count')
                ->label('Jumlah siswa')
                ->counts('siswa'),
        ])
        ->filters([
            SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun ajaran')
                ->relationship('tahunAjaran', 'nama')
                ->default(fn () => TahunAjaran::aktif()?->id),

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
