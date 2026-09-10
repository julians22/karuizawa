<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'label',
        'full_address',
        'note',
        'latitude',
        'longitude',
        'is_primary',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_primary' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
