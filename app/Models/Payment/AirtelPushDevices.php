<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirtelPushDevices extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'push_id',
        'device_id',
        'amount',
        'refrence'
    ];


    public function devices()
    {
        return $this->belongsTo(\App\Models\Payment\AirtelPush::class);
    }
}
