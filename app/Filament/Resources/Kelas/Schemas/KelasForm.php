<?php

namespace App\Filament\Resources\Kelas\Schemas;

use App\Models\TahunAjaran;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasForm
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

            TextInput::make('nama')
                ->label('Nama kelas')
                ->placeholder('Contoh: XI RPL 1')
                ->required()
                ->maxLength(50)
                ->unique(
                    ignoreRecord: true,
                    modifyRuleUsing: fn (Unique $rule, Get $get) => $rule
                        ->where('tahun_ajaran_id', $get('tahun_ajaran_id')),
                ),

            Select::make('tingkat')
                ->options(['X' => 'X', 'XI' => 'XI', 'XII' => 'XII'])
                ->required(),

            Select::make('jurusan_id')
                ->label('Jurusan')
                ->relationship('jurusan', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('wali_kelas_id')
                ->label('Wali kelas')
                ->relationship(
                    'waliKelas',
                    'name',
                    modifyQueryUsing: fn (Builder $query) => $query
                        ->where('is_active', true)
                        ->whereIn('role', ['guru', 'guru_bk', 'kesiswaan']),
                )
                ->searchable()
                ->preload()
                ->unique(
                    ignoreRecord: true,
                    modifyRuleUsing: fn (Unique $rule, Get $get) => $rule
                        ->where('tahun_ajaran_id', $get('tahun_ajaran_id')),
                )
                ->validationMessages(['unique' => 'Guru ini sudah menjadi wali kelas lain di tahun ajaran yang sama.']),
        ]);
    }
}
