<?php

namespace App\Filament\Resources\Jadwals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Imports\JadwalImporter;
use Filament\Actions\ImportAction;
use App\Filament\Resources\Jadwals\JadwalResource;

class JadwalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('hari')
                ->formatStateUsing(fn ($state) => Jadwal::HARI[$state] ?? $state)
                ->sortable(),

            TextColumn::make('jam_mulai')
                ->label('Waktu')
                ->formatStateUsing(fn ($state, Jadwal $record) =>
                    substr($record->jam_mulai, 0, 5) . ' - ' . substr($record->jam_selesai, 0, 5)),

            TextColumn::make('jam_ke_mulai')
                ->label('Jam ke-')
                ->formatStateUsing(fn ($state, Jadwal $record) =>
                    $record->jam_ke_mulai . ($record->jam_ke_selesai != $record->jam_ke_mulai ? '-' . $record->jam_ke_selesai : '')),

            TextColumn::make('kelas.nama')->label('Kelas')->searchable(),
            TextColumn::make('mapel.nama')->label('Mapel')->searchable(),
            TextColumn::make('guru.name')->label('Guru')->searchable(),
            TextColumn::make('ruang.nama')->label('Ruang')->placeholder('-'),
        ])
        ->filters([
            SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun ajaran')
                ->relationship('tahunAjaran', 'nama')
                ->default(fn () => TahunAjaran::aktif()?->id),

            SelectFilter::make('hari')->options(Jadwal::HARI),
            SelectFilter::make('kelas_id')->label('Kelas')->relationship('kelas', 'nama')->searchable()->preload(),
            SelectFilter::make('guru_id')->label('Guru')->relationship('guru', 'name')->searchable()->preload(),
        ])
        ->headerActions([
            ImportAction::make()
                ->label('Import jadwal dari CSV')
                ->importer(JadwalImporter::class)
                ->visible(fn (): bool => JadwalResource::canCreate()),
        ])
        ->defaultSort(fn (Builder $query): Builder => $query->orderBy('hari')->orderBy('jam_mulai'))
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
