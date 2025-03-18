<?php

namespace App\Models;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Get the user associated with the TargetSetting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
