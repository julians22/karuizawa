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

    public function __construct()
    {
        $this->getSign();
        $this->appToken = config('accurate.auth.app_token');
        $this->appUrl = config('accurate.auth.app_url');
    }

    function productJobs($pageSize = 100, $page = 1, $itemCategoiry = 1009, $fields = 'id,name,no,unitPrice') {
        $endpoint = $this->appUrl . '/accurate/api/item/list.do';
        $headers = [
            'Authorization' => 'Bearer ' . $this->appToken,
            'X-Api-Timestamp' => $this->timestamp,
            'X-Api-Signature' => $this->signature
        ];

        $params = [
            'pageSize' => $pageSize,
            'page' => $page,
            'fields' => $fields,
            'filter.itemCategoryId.val' => $itemCategoiry,
        ];

        $oauth = new Oauth();
        $oauth->makeSignature();

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
                dispatch(new SyncProduct($product));
            }

            $pageCount = $response['sp']['pageCount'];
            $nextPage = $page + 1;

            for($nextPage; $nextPage <= $pageCount; $nextPage++) {
                $params['page'] = $nextPage;
                dispatch(new ProductSyncJob($endpoint, $params));
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

    private function getSign(){
        $cachedAuth = cache()->get('accurate_auth');

        if ($cachedAuth) {
            $this->signature = $cachedAuth['sign'];
            $this->timestamp = $cachedAuth['timestamp'];
        } else {
            $oauth = new Oauth();
            $oauth->makeSignature();
            $cachedAuth = cache()->get('accurate_auth');
            $this->signature = $cachedAuth['sign'];
            $this->timestamp = $cachedAuth['timestamp'];
        }

        return [
            'signature' => $this->signature,
            'timestamp' => $this->timestamp
        ];
    }
}
