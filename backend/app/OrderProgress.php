<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderProgress extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'prosentase_pekerjaan',
        'keterangan',
        'status',
        'posisi',
        'checklist',
    ];

    protected $casts = ['checklist' => 'json'];

    protected $with = ['user'];

    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-M-Y H:i');
    }

    public function getKeteranganAttribute($value)
    {
        return nl2br($value);
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
