<?php

namespace App\Jobs\Accurate;

use App\Jobs\Accurate\Traits\AccurateAccess;
use App\Models\Order;
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
    protected $order_id;

    /**
     * Create a new job instance.
     */
    public function __construct($params, $order_id)
    {
        $this->order_id = $order_id;
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

            $response = json_decode($response->body(), true);
            $response = $response['r'];

            $order = Order::find($this->order_id);

            $order->downPaymentResponse->create([
                'response' => $response,
                'down_payment_number' => $response['orderDownPayment'],
                'down_payment_amount' => number_format($this->params['dpAmount'], 0, '.', ''),
            ]);

            // create activity log
            activity()
                ->performedOn($order)
                ->withProperties(['response' => $response])
                ->log('Order Down Payment created in Accurate');
        }else{
            activity()
                ->withProperties(['response' => $response->json()])
                ->log('Failed to save order to Accurate');
        }
    }
}
