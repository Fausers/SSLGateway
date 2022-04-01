<?php

namespace App\Models\CallHome;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ip',
        'asset_id',
        'power',
    ];
}
