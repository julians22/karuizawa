<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_type',
        'product_id',
        'quantity',
        'discount',
        'discount_detail',
        'price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'discount_detail' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['total_price', 'type'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->morphTo();
    }

    public function getTotalPriceAttribute()
    {
        if ($this->isReadyToWear()) {
            $price = $this->price - $this->discount;
            return $price * $this->quantity;
        }

        return $this->quantity * $this->price;
    }

    public function getTypeAttribute()
    {
        return $this->isSemiCustom() ? 'SC' : 'RTW';
    }

    public function isSemiCustom()
    {
        return $this->product_type == 'App\Models\SemiCustomProduct';
    }

    public function isReadyToWear()
    {
        return $this->product_type == 'App\Models\Product';
    }
}
