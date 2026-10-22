<?php

namespace App\Filament\Resources\Jadwals\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Builder;

class JadwalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tahun_ajaran_id')
                ->label('Tahun ajaran')
                ->relationship('tahunAjaran', 'nama')
                ->default(fn () => TahunAjaran::aktif()?->id)
                ->required()
                ->live(),

            Select::make('kelas_id')
                ->label('Kelas')
                ->relationship(
                    'kelas',
                    'nama',
                    modifyQueryUsing: fn (Builder $query, Get $get) => $query
                        ->where('tahun_ajaran_id', $get('tahun_ajaran_id')),
                )
                ->searchable()
                ->preload()
                ->required(),

            Select::make('mapel_id')
                ->label('Mata pelajaran')
                ->relationship('mapel', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('guru_id')
                ->label('Guru')
                ->relationship(
                    'guru',
                    'name',
                    modifyQueryUsing: fn (Builder $query) => $query
                        ->where('is_active', true)
                        ->whereIn('role', ['guru', 'guru_bk', 'kesiswaan']),
                )
                ->searchable()
                ->preload()
                ->required(),

            Select::make('ruang_id')
                ->label('Ruang (lab/kelas)')
                ->relationship('ruang', 'nama')
                ->searchable()
                ->preload()
                ->placeholder('Belum diatur'),

            Select::make('hari')
                ->options(Jadwal::HARI)
                ->required(),

            TextInput::make('jam_ke_mulai')
                ->label('Jam ke- (mulai)')
                ->numeric()->minValue(1)->maxValue(15)
                ->required()
                ->helperText('Jam ke-1 atau ke-2 menentukan guru boleh mengabsen kelas lewat aplikasi.'),

            TextInput::make('jam_ke_selesai')
                ->label('Jam ke- (selesai)')
                ->numeric()->minValue(1)->maxValue(15)
                ->required()
                ->gte('jam_ke_mulai'),

            TimePicker::make('jam_mulai')
                ->label('Waktu mulai')
                ->seconds(false)
                ->required(),

            TimePicker::make('jam_selesai')
                ->label('Waktu selesai')
                ->seconds(false)
                ->required()
                ->after('jam_mulai'),
        ]);
    }
}
