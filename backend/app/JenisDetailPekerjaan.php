<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisDetailPekerjaan extends Model
{
    protected $fillable = ['nama', 'keterangan', 'hidden', 'urutan'];

    protected $casts = ['hidden' => 'boolean'];
}
