<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\ProductIndexRequest;
use App\Http\Resources\Catalog\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List products currently sellable online (config-driven category
     * whitelist), filterable via query params, with live stock quantities.
     */
    public function index(ProductIndexRequest $request)
    {
        $storeId = $request->integer('store_id') ?: null;
        $perPage = min(
            $request->integer('per_page') ?: config('catalog.default_per_page', 12),
            config('catalog.max_per_page', 50)
        );

        $products = Product::query()
            ->onlineSellable($storeId)
            ->with(['category', 'brand'])
            // Live stock is always summed straight from product_actual_stocks, the same
            // table orders decrement (with row locking) at checkout time, so it already
            // reflects every completed transaction with no separate caching layer.
            ->withSum(['productActualStocks as stock_quantity' => function ($query) use ($storeId) {
                $query->when($storeId, fn ($query) => $query->where('store_id', $storeId));
            }], 'stock_quantity')
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($query) => $query->where('slug', $request->string('category')));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($query) use ($search) {
                    $query->where('product_name', 'like', "%{$search}%")
                        ->orWhere('commerce_title', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('brand'), fn ($query) => $query->where('brand_id', $request->brand))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->min_price))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->max_price))
            ->when($request->filled('sort'), function ($query) use ($request) {
                match ($request->string('sort')->toString()) {
                    'newest' => $query->orderBy('updated_at', 'desc'),
                    'oldest' => $query->orderBy('updated_at', 'asc'),
                    'price_asc' => $query->orderBy('price', 'asc'),
                    'price_desc' => $query->orderBy('price', 'desc'),
                    default => $query->orderBy('updated_at', 'desc'),
                };
            }, fn ($query) => $query->orderBy('updated_at', 'desc'))
            ->paginate($perPage)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Show a single sellable product by SKU with its live stock quantity.
     */
    public function show(Request $request, string $sku)
    {
        $storeId = $request->integer('store_id') ?: null;

        $product = Product::query()
            ->onlineSellable($storeId)
            ->with(['category', 'brand'])
            ->withSum(['productActualStocks as stock_quantity' => function ($query) use ($storeId) {
                $query->when($storeId, fn ($query) => $query->where('store_id', $storeId));
            }], 'stock_quantity')
            ->where('sku', $sku)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
