<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisDetailPekerjaan extends Model
{
    protected $fillable = ['nama', 'keterangan', 'hidden', 'urutan', 'bobot'];

    protected $casts = ['hidden' => 'boolean'];
}
