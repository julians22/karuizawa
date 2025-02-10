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
        $code = $this->store->code;
        $id = $this->id;
        $bookingCode = $code . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);

        return [
            'amount' => $this->total_price,
            'amount_formatted' => price_format($this->total_price, 0),
            'order_date' => $this->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d | H:i'),
            'status' => $this->status,
            'order_id' => $this->id,
            'customer_name' => $this->customer->full_name,
            'customer_email' => $this->customer->email ?? 'N/A',
            'customer_phone' => $this->customer->phone,
            'customer_address' => $this->customer->address ?? 'N/A',
            'customer_gender' => $this->customer->is_male ? 'Male' : 'Female',
            'booking_code' => $this->booking_code ?? $bookingCode,
            'items' => OrderItemResource::collection($this->orderItems),
            'discount' => (int) $this->discount,
            'discount_formatted' => price_format($this->discount, 0),
            'discount_details' => $this->discount_details,
        ];
    }
}
