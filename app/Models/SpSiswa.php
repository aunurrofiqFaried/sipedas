<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpSiswa extends Model
{
    protected $table = 'sp_siswa';

    protected $fillable = [
        'siswa_id', 'tahun_ajaran_id', 'aturan_sp_id', 'total_poin_saat_terbit',
        'status', 'tanggal_terbit', 'disetujui_oleh', 'catatan',
    ];

    protected function casts(): array
    {
        return ['tanggal_terbit' => 'date'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function aturan(): BelongsTo
    {
        return $this->belongsTo(AturanSp::class, 'aturan_sp_id');
    }

    public function penyetuju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
