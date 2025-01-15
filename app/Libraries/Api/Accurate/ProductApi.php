<?php

namespace App\Libraries\Api\Accurate;

use Illuminate\Http\Client\Pool;
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
    }

    public function getProducts()
    {
        // check the product_cache
        $cachedProduct = cache()->get('accurate_product');
        $data = [];

        if ($cachedProduct) {
            $data['products'] = $cachedProduct;
            $data['source'] = 'cache';
        } else {
            $products = $this->fetchProduct(100);
            cache()->put('accurate_product', $products, now()->addMinutes(5));
            $data['products'] = $products;
            $data['source'] = 'fresh';
        }

        return $data;
    }

    public function getStocks()
    {

        // check stock cache
        $cachedStock = cache()->get('accurate_stock');

        $data = [];

        if ($cachedStock) {

            $data['stocks'] = $cachedStock;
            $data['source'] = 'cache';

        }else{
            $stocks = $this->fetchStock(100);

            // cache stock
            cache()->put('accurate_stock', $stocks, now()->addMinutes(5));

            $data['stocks'] = $stocks;
            $data['source'] = 'fresh';
        }

        return $data;
    }

    /**
     *
     * Fetch product from Accurate API
     * Endpoint: /accurate/api/item/list.do
     *
     * @param integer $pageSize
     * @param integer $page
     * @param integer $itemCategoiry
     * @param string $fields
     * @return array
     **/
    protected function fetchProduct($pageSize = 10, $page = 1, $itemCategoiry = 1009, $fields = 'id,name,no,unitPrice')
    {
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

        $firstPage = Http::withHeaders($headers)
            ->get($endpoint, $params);

        if ($firstPage->status() == 200) {
            $firstPage = $firstPage->json();
            $totalPage = $firstPage['sp']['pageCount'];
            $products = $firstPage['d'];

            $responses = Http::pool(function (Pool $pool) use ($totalPage, $endpoint, $headers, $params) {
                $arrayPool = [];
                $page = 2;
                $params['page'] = $page;

                while ($page <= $totalPage) {
                    $arrayPool[] = $pool->withHeaders($headers)
                        ->get($endpoint, $params);
                    $page++;
                }

                return $arrayPool;
            });

            foreach ($responses as $response) {
                $response = $response->json();
                $products = array_merge($products, $response['d']);
            }
            return $products;
        }

        return [];
    }

    /**
     * fetch stock list from Accurate API
     * endpoint: /accurate/api/item/list-stock.do
     *
     * @param integer $pageSize
     * @param integer $page
     *
     **/
    public function fetchStock($pageSize = 10, $page = 1)
    {
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

        $firstPage = Http::withHeaders($headers)
            ->get($endpoint, $params);

        if ($firstPage->status() == 200) {
            $firstPage = $firstPage->json();
            $totalPage = $firstPage['sp']['pageCount'];
            $stocks = $firstPage['d'];

            $responses = Http::pool(function (Pool $pool) use ($totalPage, $endpoint, $headers, $params) {
                $arrayPool = [];
                $page = 2;

                while ($page <= $totalPage) {
                    $params['sp.page'] = $page;
                    $arrayPool[] = $pool->withHeaders($headers)
                        ->get($endpoint, $params);
                    $page++;
                }

                return $arrayPool;
            });

            foreach ($responses as $response) {
                $response = $response->json();
                $stocks = array_merge($stocks, $response['d']);
            }

            return $stocks;
        }
    }
}
