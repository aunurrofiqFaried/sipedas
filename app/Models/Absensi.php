<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';

    public const STATUS = ['hadir', 'sakit', 'izin', 'alpa'];

    protected $fillable = ['siswa_id', 'kelas_id', 'tanggal', 'status', 'keterangan', 'diinput_oleh'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function penginput(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }
}
