<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemiCustomOuterProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'basic_form' => 'array',
        'option_form' => 'array',
        'size' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['fabric_code', 'fabric_name', 'collar', 'cuff', 'button', 'order_type_customer', 'order_number'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'product');
    }

    public function isFinish() {
        return $this->status == 'finish' ? true : false;
    }

    public function getOrderNumberAttribute()
    {
        $orderItem = $this->orderItem;
        if($orderItem != null) {
            $orderNumber = explode('-', $orderItem->order->order_number);
            $newOrderNumber = $orderNumber[0].'-'.str_pad($this->id, 5, '0', STR_PAD_LEFT);;
            // $newOrderNumber = $orderItem->order->order_number.'-'. $this->id;

            return $newOrderNumber;
        }
        return 'N/A';
    }
}
