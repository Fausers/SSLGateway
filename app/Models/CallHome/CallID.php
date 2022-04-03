<?php

namespace App\Models\CallHome;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallID extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'radio',
        'mcc',
        'net',
        'area',
        'cell',
        'unit',
        'lon',
        'lat',
        'range',
        'samples',
        'changeable',
        'created',
        'updated',
        'average_signal',
    ];
}
