<?php

namespace App\Jobs;

use App\Libraries\Api\Accurate\Oauth;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
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
                dispatch(new SyncProduct($product));
            }
        }else{
            $this->release(10);
        }
    }

    private function getSign(){

        $timestamp = "";
        $signature = "";

        $oauth = new Oauth();
        $oauth->authInfo();

        $cachedAuth = cache()->get('accurate_auth');
        $timestamp = $cachedAuth['timestamp'];
        $signature = $cachedAuth['sign'];

        return [
            'timestamp' => $timestamp,
            'signature' => $signature
        ];
    }
}
