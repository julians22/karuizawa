<?php

namespace App\Libraries\Api\Accurate;

use App\Jobs\ProductStockSyncJob;
use App\Jobs\ProductSyncJob;
use App\Jobs\SyncProductStock;
use Illuminate\Support\Facades\Http;
use App\Jobs\SyncProduct;
use App\Models\Product;
use App\Models\Store;

class ProductApi
{
    protected string|null $signature;
    protected string|null $timestamp;
    protected string|null $appToken;
    protected string|null $appUrl;

    protected array $cachedAuth;
    protected array $cachedDb;

    public function __construct()
    {
        $this->appUrl = config('accurate.auth.app_url');
        $this->cachedAuth = cache()->get('accurate_token');
        $this->cachedDb = cache()->get('accurate_db');
    }

    public function productJobs($pageSize = 20, $page = 1, $itemCategory = 1009, $fields = 'id,name,no,unitPrice') {
        $endpoint = $this->appUrl . '/accurate/api/item/list.do';

        $headers = [
            'Authorization' => 'Bearer ' . $this->cachedAuth['access_token'],
            'X-Session-ID' => $this->cachedDb['session'],
        ];
        $params = [
            'sp.pageSize' => $pageSize,
            'sp.page' => $page,
            'fields' => $fields,
            'filter.itemCategoryId.val' => $itemCategory,
        ];

        $response = Http::withHeaders($headers)
            ->get($endpoint, $params);

        if ($response->status() == 200) {
            $response = $response->json();
            $data = $response['d'];

            $products = collect($data)->map(function ($product) {

                $productData = [
                    'sku' => $product['no'],
                    'product_name' => $product['name'],
                    'price' => $product['unitPrice'] ?? 0,
                ];

                return $productData;
            });

            foreach ($products as $product) {
                // Dispatch the job to sync the product when the product price is not 0
                if ($product['price'] > 0) {
                    dispatch(new SyncProduct($product));
                }
            }

            $pageCount = $response['sp']['pageCount'];

            if ($pageCount > 0) {
                $nextPage = $page + 1;
                for($nextPage; $nextPage <= $pageCount; $nextPage++) {
                    $params['page'] = $nextPage;
                    dispatch(new ProductSyncJob($endpoint, $params));
                }
            }

            return true;
        }

        return false;
    }

    function stockJobs($pageSize = 100, $page = 1) {

        $stores = Store::whereNotNull('accurate_alias')
            ->orderBy('updated_at', 'asc')
            ->get();

        foreach ($stores as $store) {
            $endpoint = $this->appUrl . '/accurate/api/item/list-stock.do';
            $headers = [
                'Authorization' => 'Bearer ' . $this->cachedAuth['access_token'],
                'X-Session-ID' => $this->cachedDb['session'],
            ];

            $params = [
                'sp.pageSize' => $pageSize,
                'sp.page' => $page,
                'sp.sort' => 'no|asc',
                'warehouseId' => (int) $store->accurate_alias,
                'asOfDate' => date('d/m/Y'),
                'filter.itemCategoryId.val' => 350,
            ];

            $response = Http::withHeaders($headers)
                ->get($endpoint, $params);

            if ($response->status() == 200) {
                $response = $response->json();
                $data = $response['d'];

                $stocks = collect($data)->map(function ($stock) use ($store) {

                    $findProduct = Product::where('sku', $stock['no'])->first();
                    if ($findProduct) {

                        $stockData = [
                            'product_id' => $findProduct->id,
                            'store_id' => $store->id,
                            'stock_quantity' => $stock['quantity'] < 0 ? 0 : $stock['quantity']
                        ];
                        return $stockData;
                    }
                });

                foreach ($stocks as $stock) {

                    if ($stock && $stock['stock_quantity'] > 0) {
                        dispatch(new SyncProductStock($stock));
                    }
                }

                $pageCount = $response['sp']['pageCount'];

                if ($pageCount > 0) {

                    $nextPage = $page + 1;

                    for($nextPage; $nextPage <= $pageCount; $nextPage++) {
                        $params['sp.page'] = $nextPage;
                        dispatch(new ProductStockSyncJob($endpoint, $params, $store));
                    }
                }

                return true;
            }

            return false;
        }
    }
}
