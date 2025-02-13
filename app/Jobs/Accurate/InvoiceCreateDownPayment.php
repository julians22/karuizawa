<?php

namespace App\Jobs\Accurate;

use App\Jobs\Accurate\Traits\AccurateAccess;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class InvoiceCreateDownPayment implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AccurateAccess;

    protected $params;

    /**
     * Create a new job instance.
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $endpoint = config('accurate.auth.app_url') . '/accurate/api/sales-invoice/create-down-payment.do';
        $headers = $this->getHeaders();

        $response = Http::withHeaders($headers)
            ->post($endpoint, $this->params);

        if ($response->status() == 200) {

            activity()
                ->withProperties(['response' => $response->json()])
                ->log('Order saved to Accurate');
        }else{
            activity()
                ->withProperties(['response' => $response->json()])
                ->log('Failed to save order to Accurate');
        }
    }
}
