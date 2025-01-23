<?php

namespace App\Models;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPersonalData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'is_male'
    ];

    /**
     * Get the user that owns the UserPersonalData
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
