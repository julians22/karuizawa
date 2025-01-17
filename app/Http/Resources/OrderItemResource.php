<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $product_name = "";
        if ($this->isSemiCustom()) {
            $product_name = $this->product->name . ' - ' . $this->product->code;
        }else if ($this->isReadyToWear()) {
            $product_name = $this->product->product_name;
        }

        $total_price = $this->price * $this->quantity;


        return [
            'product_id' => $this->product_id,
            'product_name' => $product_name,
            'price' => $this->price,
            'price_formatted' => price_format($this->price, 0),
            'qty' => $this->quantity,
            'total_price' => $total_price,
            'total_price_formatted' => price_format($total_price, 0),
        ];
    }
}
