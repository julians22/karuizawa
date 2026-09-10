<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model implements AuthenticatableContract
{
    use Authenticatable, HasApiTokens, HasFactory, Notifiable;

    const MALE_LABEL = 'Male';
    const FEMLAE_LABEL = 'Female';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'is_male'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['gender'];


    public function getGenderAttribute()
    {
        return $this->is_male ? self::MALE_LABEL : self::FEMLAE_LABEL;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function primaryAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('is_primary', true);
    }
}
