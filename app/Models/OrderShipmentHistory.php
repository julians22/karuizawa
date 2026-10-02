<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipmentHistory extends Model
{
    protected $fillable = [
        'order_shipment_id',
        'status',
        'note',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];

    public function shipment()
    {
        return $this->belongsTo(OrderShipment::class, 'order_shipment_id');
    }
}
