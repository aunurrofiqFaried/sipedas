<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanSp extends Model
{
    protected $table = 'aturan_sp';

    protected $fillable = ['level', 'nama', 'batas_poin', 'keterangan'];
}
