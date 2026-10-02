<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ALLOCATED = 'allocated';
    const STATUS_PICKING_UP = 'picking_up';
    const STATUS_PICKED = 'picked';
    const STATUS_DROPPING_OFF = 'dropping_off';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COURIER_NOT_FOUND = 'courier_not_found';

    protected $fillable = [
        'order_id',
        'customer_address_id',
        'recipient_name',
        'recipient_phone',
        'recipient_email',
        'recipient_address',
        'area_id',
        'area_name',
        'postal_code',
        'latitude',
        'longitude',
        'courier_company',
        'courier_type',
        'courier_name',
        'courier_service_name',
        'shipping_cost',
        'insurance_cost',
        'biteship_order_id',
        'biteship_tracking_id',
        'waybill_id',
        'status',
        'note',
        'raw_request',
        'raw_response',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'shipping_cost' => 'float',
        'insurance_cost' => 'float',
        'raw_request' => 'array',
        'raw_response' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customerAddress()
    {
        return $this->belongsTo(CustomerAddress::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderShipmentHistory::class);
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }
}
