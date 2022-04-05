<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'service_id',
        'trans_id',
        'amount',
        'payment_status',
        'reference_no',
        'payment_receipt',
        'msnid',
        'trans_date',
        'opco',
        'api_username',
        'api_password',
        'payment_status_desc',
        'trans_timestamp'
    ];

}
