<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'month',
        'target'
    ];

    // disable timestamps
    public $timestamps = false;
}
