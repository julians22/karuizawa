<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Store;
use Bus;
use Http;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductStockSyncJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

            $stocks = [];

            foreach ($data as $key => $value) {
                $findProduct = Product::where('sku', $value['no'])->first();
                if ($findProduct) {
                    $quantity = $value['quantity'] < 0 ? 0 : $value['quantity'];

                    if ($quantity > 0) {

                        $stockData = [
                            'product_id' => $findProduct->id,
                            'store_id' => $store->id,
                            'stock_quantity' => $quantity
                        ];
                        $stocks[] = $stockData;
                    }
                }
            }

            $jobs = [];

            foreach ($stocks as $stock) {
                if ($stock && $stock['stock_quantity'] > 0) {
                    $jobs[] = new SyncProductStock($stock);
                }
            }

            Bus::batch($jobs)->onQueue('default')->dispatch();
        }
    }
}
