<?php

namespace App\Http\Controllers\Api\OnlineSales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Shipping\BiteshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends Controller
{
    public function __construct(protected BiteshipService $biteship)
    {
    }

    public function areas(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:3',
        ]);

        try {
            $result = $this->biteship->searchAreas($request->input('keyword'));
        } catch (\Throwable $th) {
            Log::error('Biteship searchAreas failed: ' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Shipping provider is currently unavailable, please try again.',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'data' => $result['areas'] ?? [],
        ]);
    }

    public function rates(Request $request)
    {
        $request->validate([
            'area_id' => 'required|string',
            'postal_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|exists:products,sku',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $items = collect($request->input('items'))->map(function ($item) {
            $product = Product::where('sku', $item['sku'])->first();

            return [
                'name' => $product->product_name ?? $item['sku'],
                'value' => (float) ($product->price ?? 0),
                'quantity' => (int) $item['qty'],
                'weight' => (int) config('biteship.default_item_weight_gram'),
            ];
        })->values()->all();

        try {
            $result = $this->biteship->getRates([
                'area_id' => $request->input('area_id'),
                'postal_code' => $request->input('postal_code'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ], $items);
        } catch (\Throwable $th) {
            Log::error('Biteship getRates failed: ' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Shipping provider is currently unavailable, please try again.',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'data' => $result['pricing'] ?? [],
        ]);
    }
}
