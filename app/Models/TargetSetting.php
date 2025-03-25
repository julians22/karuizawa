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
        'target',
        'category_id',
        'is_semicustom',
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

    /**
     * Get the store associated with the TargetSetting
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * Get the category associated with the TargetSetting
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function isSemiCustom(): bool
    {
        return $this->is_semicustom;
    }

    // scope to get target setting semi custom
    public function scopeSemiCustom($query)
    {
        return $query->where('is_semicustom', true);
    }
}
