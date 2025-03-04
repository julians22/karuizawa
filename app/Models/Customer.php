<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

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
}
