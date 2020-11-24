<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_TERDAFTAR = 0;

    const STATUS_DALAM_PENGERJAAN = 10;

    const STATUS_SELESAI = 20;

    protected $fillable = [
        'nomor',
        'tanggal_masuk',
        'tanggal_keluar',
        'sarana_id',
        'jenis_sarana_id',
        'dipo_id',
        'jalur_id',
        'jenis_pekerjaan_id',
        'prosentase_pekerjaan',
        'status',
        'user_id',
        'keterangan',
        'by_pa_akhir',
        'tgl_pa_akhir',
        'mulai_dinas',
        'bogie_id'
    ];

    protected $appends = ['status_label', 'status_type'];

    public function getStatusLabelAttribute()
    {
        $status = [
            self::STATUS_TERDAFTAR => 'TERDAFTAR',
            self::STATUS_DALAM_PENGERJAAN => 'PROSES',
            self::STATUS_SELESAI => 'SELESAI'
        ];

        return isset($status[$this->status]) ? $status[$this->status] : $status[self::STATUS_TERDAFTAR];
    }

    public function getStatusTypeAttribute()
    {
        $types = [
            self::STATUS_TERDAFTAR => 'info',
            self::STATUS_DALAM_PENGERJAAN => 'warning',
            self::STATUS_SELESAI => 'success'
        ];

        return isset($types[$this->status]) ? $types[$this->status] : $types[self::STATUS_TERDAFTAR];
    }

    public function orderProgress()
    {
        return $this->hasMany(OrderProgress::class)->orderBy('created_at', 'desc');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sarana()
    {
        return $this->belongsTo(Sarana::class);
    }

    public function jenisSarana()
    {
        return $this->belongsTo(JenisSarana::class);
    }

    public function dipo()
    {
        return $this->belongsTo(Dipo::class);
    }

    public function jalur()
    {
        return $this->belongsTo(Jalur::class);
    }

    public function jenisPekerjaan()
    {
        return $this->belongsTo(JenisPekerjaan::class);
    }

    public function bogie()
    {
        return $this->belongsTo(Bogie::class);
    }

    public function scopeChart($q)
    {
        return $q->whereHas('jenisPekerjaan', function ($q) {
            $q->where('tampilkan_di_grafik', 1);
        });
    }
}
