<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KontakWali extends Model
{
    protected $table = 'kontak_wali';

    protected $fillable = ['siswa_id', 'dihubungi_oleh', 'tujuan', 'catatan'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function penghubung(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dihubungi_oleh');
    }
}