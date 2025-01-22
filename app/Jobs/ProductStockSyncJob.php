<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Store;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ProductStockSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $endpoint;
    protected $params;
    protected $store;

    /**
     * Create a new job instance.
     */
    public function __construct($endpoint, $params, Store $store)
    {
        $this->endpoint = $endpoint;
        $this->params = $params;
        $this->store = $store;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $endpoint = $this->endpoint;

        $accessToken = cache()->get('accurate_token')['access_token'];
        $sessionId = cache()->get('accurate_db')['session'];

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'X-Session-ID' => $sessionId,
        ];

        $response = Http::withHeaders($headers)
            ->get($endpoint, $this->params);

        if ($response->status() == 200) {

            $response = $response->json();
            $data = $response['d'];
            $store = $this->store;

            $stocks = collect($data)->map(function ($stock) use ($store) {
                $findProduct = Product::where('sku', $stock['no'])->first();
                if ($findProduct) {
                    $quantity = $stock['quantity'] < 0 ? 0 : $stock['quantity'];

                    $stockData = [
                        'product_id' => $findProduct->id,
                        'store_id' => $store->id,
                        'stock_quantity' => $quantity
                    ];
                    return $stockData;
                }
            });

            foreach ($stocks as $stock) {
                if ($stock && $stock['stock_quantity'] > 0) {
                    dispatch(new SyncProductStock($stock));
                }
            }
        }
    }
}
