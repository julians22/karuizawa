<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownPaymentResponse extends Model
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
        'down_payment_created_date' => 'datetime',
        'response' => 'array',
    ];

    public function isInvoiced()
    {
        return $this->invoice_number !== null;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
