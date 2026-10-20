<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jurnal extends Model
{
    protected $table = 'jurnal';

    protected $fillable = ['jadwal_id', 'guru_id', 'kelas_id', 'tanggal', 'materi', 'catatan'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
