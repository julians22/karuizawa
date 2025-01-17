<?php

namespace App\Jobs;

use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $endpoint;
    protected $params;

    /**
     * Create a new job instance.
     */
    public function __construct($endpoint, $params)
    {
        $this->endpoint = $endpoint;
        $this->params = $params;
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
        }else{
            $this->release(10);
        }
    }
}
