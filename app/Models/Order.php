<?php

namespace App\Models;

use App\Domains\Auth\Models\User;
use App\Models\Traits\Uniqueid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, Uniqueid;

    protected $fillable = [
        'uuid',
        'customer_id',
        'store_id',
        'user_id',
        'total_price',
        'discount',
        'discount_details',
        'payment',
        'bank',
        'status',
        'order_number',
        'order_date',
    ];

    protected $casts = [
        'discount_details' => 'array',
    ];

    // order date yyyy-mm-dd
    protected $dates = ['order_date'];

    protected $appends = ['down_payment_amount', 'completion_amount', 'remaining_amount'];

    protected $with = ['payments'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDownPaymentAmountAttribute()
    {
        return $this->payments()->where('is_downpayment', 1)->sum('amount');
    }

    public function getCompletionAmountAttribute()
    {
        return $this->payments()->where('is_downpayment', 0)->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_price - $this->payments()->sum('amount');
    }

    public function hasDownPayment()
    {
        return $this->payments()->where('is_downpayment', 1)->exists();
    }

    public function hasCompletionPayment()
    {
        return $this->payments()->where('is_downpayment', 0)->exists();
    }

    public function isPaymentComplete()
    {
        $totalPayments = $this->payments()->sum('amount');
        return $totalPayments == $this->total_price;
    }
}
