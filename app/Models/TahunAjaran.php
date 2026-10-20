<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = ['nama', 'tanggal_mulai', 'tanggal_selesai', 'is_active'];

    protected static function booted(): void
    {
        static::saved(function (TahunAjaran $tahunAjaran) {
            if ($tahunAjaran->is_active) {
                static::where('id', '!=', $tahunAjaran->id)->update(['is_active' => false]);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function aktif(): ?self
    {
        return static::query()->aktif()->first();
    }
}
