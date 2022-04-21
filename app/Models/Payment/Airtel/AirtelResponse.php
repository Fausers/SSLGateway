<?php

namespace App\Models\Payment\Airtel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirtelResponse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'airtel_pushes_id',
        'trans_id',
        'message',
        'status_code',
        'airtel_money_id',
    ];
}
