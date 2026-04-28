<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    const PRODUCT_TYPE_RTW = 'App\Models\Product';
    const PRODUCT_TYPE_SC = 'App\Models\SemiCustomProduct';
    const PRODUCT_TYPE_SCO = 'App\Models\SemiCustomOuterProduct';

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
    protected $appends = ['total_price', 'type', 'discount_percentage'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->morphTo();
    }

    public function product_rtw()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_sc()
    {
        return $this->belongsTo(SemiCustomProduct::class, 'product_id');
    }

    public function product_sco()
    {
        return $this->belongsTo(SemiCustomOuterProduct::class, 'product_id');
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
        // return $this->isSemiCustom() ? 'SC' : 'RTW';
        // Type must be SC or RTW, if product type is SemiCustomProduct or SemiCustomOuterProduct then return SC, else return RTW
        if ($this->isSemiCustom()) {
            return 'SC';
        } else if ($this->isSemiCustomOuter()) {
            return 'SCO';
        } else {
            return 'RTW';
        }
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->isReadyToWear()) {
            if ($this->discount && array_key_exists('discount', $this->discount_detail)) {
                return $this->discount_detail['discount'];
            }
        }

        return 0;
    }

    public function isSemiCustom()
    {
        return $this->product_type == 'App\Models\SemiCustomProduct';
    }

    public function isSemiCustomOuter()
    {
        return $this->product_type == 'App\Models\SemiCustomOuterProduct';
    }

    public function isReadyToWear()
    {
        return $this->product_type == 'App\Models\Product';
    }

    /**
     * Scope a query to only include ready to wear products.
     */
    public function scopeReadyToWear($query)
    {
        return $query->where('product_type', 'App\Models\Product');
    }

    /**
     * Scope a query to only include semi custom products.
     */
    public function scopeSemiCustom($query)
    {
        return $query->where('product_type', 'App\Models\SemiCustomProduct');
    }

    /**
     * Scope a query to only include semi custom outer products.
     */
    public function scopeSemiCustomOuter($query)
    {
        return $query->where('product_type', 'App\Models\SemiCustomOuterProduct');
    }
}
