<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentReference extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'payment_reference',
        'account',
    ];
}
