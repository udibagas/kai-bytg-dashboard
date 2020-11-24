<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sarana extends Model
{
    protected $fillable = [
        'nomor',
        'nomor_lama',
        'jenis_sarana_id',
        'dipo_id',
        'bogie_id',
        'keterangan'
    ];

    public function dipo()
    {
        return $this->belongsTo(Dipo::class);
    }

    public function jenisSarana()
    {
        return $this->belongsTo(JenisSarana::class);
    }

    public function bogie()
    {
        return $this->belongsTo(Bogie::class);
    }
}
