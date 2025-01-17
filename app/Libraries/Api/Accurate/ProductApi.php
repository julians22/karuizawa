<?php

namespace App\Libraries\Api\Accurate;

use App\Jobs\ProductStockSyncJob;
use App\Jobs\ProductSyncJob;
use App\Jobs\SyncProduct;
use App\Jobs\SyncProductStock;
use Illuminate\Support\Facades\Http;

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
            'pageSize' => $pageSize,
            'page' => $page,
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
                // Dispatch job to sync product
                dispatch(new SyncProduct($product));
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

    function stockJobs($pageSize = 100, $page = 1, $wareHouseName = 'Ashta') {
        $endpoint = $this->appUrl . '/accurate/api/item/list-stock.do';
        $headers = [
            'Authorization' => 'Bearer ' . $this->appToken,
            'X-Api-Timestamp' => $this->timestamp,
            'X-Api-Signature' => $this->signature
        ];

        $params = [
            'sp.pageSize' => $pageSize,
            'sp.page' => $page,
            'sp.sort' => 'no|asc',
            'wareHouseName' => 'Ashta',
            'asOfDate' => date('d/m/Y'),
        ];

        $oauth = new Oauth();
        $oauth->makeSignature();

        $response = Http::withHeaders($headers)
            ->get($endpoint, $params);

        if ($response->status() == 200) {
            $response = $response->json();
            $data = $response['d'];

            $stocks = collect($data)->map(function ($stock) {
                $quantity = $stock['quantity'] < 0 ? 0 : $stock['quantity'];
                $productData = [
                    'sku' => $stock['no'],
                    'daily_stock' => $quantity
                ];

                return $productData;
            });

            foreach ($stocks as $stock) {
                dispatch(new SyncProductStock($stock));
            }

            $pageCount = $response['sp']['pageCount'];
            $nextPage = $page + 1;

            for($nextPage; $nextPage <= $pageCount; $nextPage++) {
                $params['sp.page'] = $nextPage;
                dispatch(new ProductStockSyncJob($endpoint, $params));
            }

            return true;
        }

        return false;
    }
}
