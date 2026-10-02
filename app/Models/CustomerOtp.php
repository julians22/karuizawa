<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOtp extends Model
{
    public const PURPOSE_LOGIN = 'login';
    public const PURPOSE_REGISTER = 'register';
    public const PURPOSE_RESET_PASSWORD = 'reset_password';
    public const PURPOSE_SET_PASSWORD = 'set_password';

    protected $fillable = [
        'customer_id',
        'email',
        'purpose',
        'otp_hash',
        'attempts',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }
}
