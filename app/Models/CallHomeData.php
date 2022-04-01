<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallHomeData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'call_home_id',
        'realtime',
        'total_uptime',
        'session_uptime',
        'vpanel',
        'vout',
        'isns',
        'carrier',
        'lac',
        'ci',
        'rssi',
        'ber',
        'lat',
        'lon',
        'country',
        'area'
    ];

    public function call_home()
    {
        return $this->belongsTo(\App\Models\CallHome::class);
    }
}
