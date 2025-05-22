<?php

namespace App\Jobs\Accurate;

use App\Jobs\Accurate\Traits\AccurateAccess;
use App\Models\Order;
use Http;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceFinishDownPayment implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AccurateAccess;

    protected $params;
    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct($params, Order $order)
    {
        $this->params = $params;
        $this->order = $order;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $endpoint = config('accurate.auth.app_url') . '/accurate/api/sales-receipt/save.do';
        $headers = $this->getHeaders();

        $response = Http::withHeaders($headers)
            ->post($endpoint, $this->params);

        if ($response->status() == 200) {
            $response = json_decode($response->body(), true);
            $response = $response['r'];

            // create activity log
            activity()
                ->performedOn($this->order)
                ->withProperties(['response' => $response])
                ->log('Order Down Payment Receipt created in Accurate');
        } else {

            $response = json_decode($response->body(), true);
            $response = $response['r'];

            // create activity log
            activity()
                ->performedOn($this->order)
                ->withProperties(['response' => $response])
                ->log('Order Down Payment failed in Accurate');
        }
    }
}
