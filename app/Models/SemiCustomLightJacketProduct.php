<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemiCustomLightJacketProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['fabric_code', 'fabric_name', 'order_type_customer', 'order_number'];

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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'product');
    }

    public function orderItem()
    {
        return $this->morphOne(OrderItem::class, 'product');
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

    public function getFabricCodeAttribute()
    {
        $basic = $this->basic_form;
        if(isset($basic['fabric']) && count($basic['fabric']) > 0) {
            return $basic['fabric']['fabricCode'] ?? 'N/A';
        }
    }

    public function getFabricNameAttribute()
    {
        $basic = $this->basic_form;

        if(isset($basic['fabric']) && count($basic['fabric']) > 0) {
            return $basic['fabric']['text'] ?? 'N/A';
        }

    }

    public function getOrderTypeCustomerAttribute()
    {
        $size = $this->size;

        if(isset($size['order']) && $size['order'] != null) {
            return $size['order'] ?? 'N/A';
        }

        return 'N/A';
    }
}
