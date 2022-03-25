<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirtelPush extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'reference',
        'amount',
        'country',
        'currency',
        'msisnd',
        'ssl_id',
        'message',
        'status_code',
        'airtel_money_id'
    ];
}
