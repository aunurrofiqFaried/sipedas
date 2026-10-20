<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
    'nis', 'nisn', 'nama', 'jenis_kelamin',
    'nama_wali', 'hubungan_wali', 'no_hp_wali',
    'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function kelasSiswa(): HasMany
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function pelanggaran(): HasMany
    {
        return $this->hasMany(PelanggaranSiswa::class);
    }

    public function spSiswa(): HasMany
    {
        return $this->hasMany(SpSiswa::class);
    }

    // Total poin pelanggaran dalam satu tahun ajaran (poin reset per tahun ajaran)
    public function totalPoin(int $tahunAjaranId): int
    {
        return (int) $this->pelanggaran()
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->sum('poin');
    }
}
