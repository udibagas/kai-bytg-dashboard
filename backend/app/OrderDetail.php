<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'jenis_detail_pekerjaan_id',
        'prosentase_pekerjaan',
        'keterangan'
    ];

    protected $with = ['jenisDetailPekerjaan', 'user'];

    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-M-Y H:i');
    }

    public function jenisDetailPekerjaan()
    {
        return $this->belongsTo(JenisDetailPekerjaan::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
