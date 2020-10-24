<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    protected $fillable = [
        'tahun',
        'bulan',
        'jenis_sarana_id',
        'jenis_pekerjaan_id',
        'target'
    ];

    protected $appends = ['nama_bulan'];

    public function jenisSarana()
    {
        return $this->belongsTo(JenisSarana::class);
    }

    public function jenisPekerjaan()
    {
        return $this->belongsTo(JenisPekerjaan::class);
    }

    public function getNamaBulanAttribute()
    {
        $bulan = [
            '', 'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus', '
            September', 'Oktober',  'November', 'Desember'
        ];

        return $bulan[$this->bulan];
    }

    public function scopeChart($q)
    {
        return $q->whereHas('jenisPekerjaan', function ($q) {
            $q->where('tampilkan_di_grafik', 1);
        });
    }
}
