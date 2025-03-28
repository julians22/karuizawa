<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'address', 'accurate_alias'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function targets()
    {
        return $this->hasMany(TargetSetting::class);
    }

}
