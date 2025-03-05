<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemiCustomProduct extends Model
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
    protected $appends = ['fabric_code', 'fabric_name', 'collar', 'cuff', 'front_body', 'pocket', 'back_body', 'button', 'order_type_customer', 'body_type', 'sleeve', 'order_number'];

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

    // todo: add more accessor

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

    public function getCollarAttribute()
    {
        $options = $this->option_form;
        if(isset($options['collar']) && count($options['collar']) > 0) {
            return $options['collar']['name'] ?? 'N/A';
        }else{
            $basic = $this->basic_form;

            if(isset($basic['collar']) && count($basic['collar']) > 0) {
                return $basic['collar']['name'] ?? 'N/A';
            }
        }
        return 'N/A';
    }

    public function getCuffAttribute()
    {
        $options = $this->option_form;
        if(isset($options['cuffs']) && count($options['cuffs']) > 0) {
            return $options['cuffs']['name'] ?? 'N/A';
        }else{
            $basic = $this->basic_form;

            if(isset($basic['cuff']) && count($basic['cuff']) > 0) {
                return $basic['cuff']['name'] ?? 'N/A';
            }
        }
        return 'N/A';
    }

    public function getFrontBodyAttribute()
    {
        $options = $this->option_form;

        if(isset($options['frontBody']) && count($options['frontBody']) > 0) {
            return $options['frontBody']['name'] ?? 'N/A';
        }else{
            $basic = $this->basic_form;

            if(isset($basic['frontBody']) && count($basic['frontBody']) > 0) {
                return $basic['frontBody']['name'] ?? 'N/A';
            }
        }
        return 'N/A';
    }

    public function getPocketAttribute()
    {
        $basic = $this->basic_form;

        if(isset($basic['pocket']) && count($basic['pocket']) > 0) {
            return $basic['pocket']['name'] ?? 'N/A';
        }

        return 'N/A';
    }

    public function getBackBodyAttribute()
    {
        $basic = $this->basic_form;

        if(isset($basic['backBody']) && count($basic['backBody']) > 0) {
            return $basic['backBody']['name'] ?? 'N/A';
        }

        return 'N/A';
    }

    public function getButtonAttribute()
    {
        $basic = $this->basic_form;

        if(isset($basic['button']) && count($basic['button']) > 0) {
            return $basic['button']['name'] ?? 'N/A';
        }

        return 'N/A';
    }

    public function getOrderTypeCustomerAttribute()
    {
        $size = $this->size;

        if(isset($size['order']) && $size['order'] != null) {
            return $size['order'] ?? 'N/A';
        }

        return 'N/A';
    }


    public function getBodyTypeAttribute()
    {
        $size = $this->size;

        if(isset($size['bodyType']) && $size['bodyType'] != null) {
            return $size['bodyType'] ?? 'N/A';
        }

        return 'N/A';
    }

    public function getSleeveAttribute()
    {
        $size = $this->size;

        if(isset($size['sleeve']) && $size['sleeve'] != null) {
            return $size['sleeve'] ?? 'N/A';
        }

        return 'N/A';
    }

}
