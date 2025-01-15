<?php

use App\Models\Product;
use App\Services\BaseService;

class ProductService extends BaseService
{

    /**
     * ProductService constructor.
     *
     * @param  Product  $product
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function upsertStocks(array $stocks = [])
    {
        DB::beginTransaction();
        try {
            $data = Product::upsert($stocks, ['sku'], ['product_name', 'daily_stock', 'description']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();

        return $data;
    }

    public function upsertPrices(array $prices = [])
    {
        DB::beginTransaction();
        try {
            $data = Product::upsert($prices, ['sku'], ['price']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();

        return $data;
    }

}
