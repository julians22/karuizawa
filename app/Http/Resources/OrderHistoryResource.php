<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->total_price,
            'amount_formatted' => price_format($this->total_price, 0),
            'order_date' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'order_id' => $this->id,
            'customer_name' => $this->customer->full_name,
            'customer_email' => $this->customer->email ?? 'N/A',
            'customer_phone' => $this->customer->phone,
            'customer_address' => $this->customer->address ?? 'N/A',
            'customer_gender' => $this->customer->is_male ? 'Male' : 'Female',
            'booking_code' => $this->store->code . '-' . $this->id,
            'items' => OrderItemResource::collection($this->orderItems),
        ];
    }
}
