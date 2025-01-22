<?php

namespace App\Jobs;

use Bus;
use Http;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductSyncJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

            $jobs = [];

            foreach ($products as $product) {
                // Dispatch the job to sync the product when the product price is not 0
                $jobs[] = new SyncProduct($product);
            }

            // can be optimized by dispatching the jobs in batch
            // chunk the jobs into smaller batch

            $chunkedJobs = array_chunk($jobs, 100);

            foreach ($chunkedJobs as $chunk) {
                Bus::batch($chunk)->onQueue('default')->dispatch();
            }
        }
    }
}
