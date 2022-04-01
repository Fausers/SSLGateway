<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallHome extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ip',
        'status',
        'version',
        'power',
        'comm_type',
        'command',
        'device_id',
    ];

    public function data()
    {
        return $this->hasMany(\App\Models\CallHomeData::class);
    }
}
