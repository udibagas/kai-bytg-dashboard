<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisPekerjaan extends Model
{
    protected $fillable = [
        'nama',
        'kode',
        'tampilkan_di_grafik',
        'tampilkan_di_slide'
    ];

    protected $casts = [
        'tampilkan_di_grafik' => 'boolean',
        'tampilkan_di_slide' => 'boolean',
    ];
}
