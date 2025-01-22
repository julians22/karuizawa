<?php

namespace App\Jobs;

use App\Models\ProductActualStock;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class SyncProductStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    // withoutOverlapping() method is used to prevent the same job from being executed multiple times
    // public function middleware()
    // {
    //     return [new WithoutOverlapping($this->data['product_id'] . "-" . $this->data['store_id'])];
    // }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Update or create product stock
        $actualStock = ProductActualStock::firstOrCreate([
            'product_id' => $this->data['product_id'],
            'store_id' => $this->data['store_id']
        ]);

        $actualStock->stock_quantity = $this->data['stock_quantity'];
        $actualStock->save();
    }
}
