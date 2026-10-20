<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelanggaranSiswa extends Model
{
    protected $table = 'pelanggaran_siswa';

    protected $fillable = [
        'siswa_id', 'tahun_ajaran_id', 'jenis_pelanggaran_id',
        'poin', 'tanggal', 'keterangan', 'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
