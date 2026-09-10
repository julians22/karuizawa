<?php

namespace App\Http\Resources\Catalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        // stock_quantity is a SUM() aggregate (withSum) over product_actual_stocks, the table
        // orders decrement in real time at checkout, so this is never a cached/stale value.
        // Clamped to 0 defensively in case a row was ever pushed negative upstream.
        $stock = max(0, (int) ($this->stock_quantity ?? 0));
        $price = $this->commerce_price ?: $this->price;

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->commerce_title ?: $this->product_name,
            'description' => $this->commerce_description ?? $this->description,
            'price' => (float) $price,
            'price_formatted' => price_format($price, 0),
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null),
            'brand' => $this->whenLoaded('brand', fn () => $this->brand ? [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ] : null),
            'image' => $this->getFirstMediaUrl('featured_image') ?: null,
            'stock' => $stock,
            'in_stock' => $stock > 0,
            'updated_at' => $this->updated_at,
        ];
    }
}
