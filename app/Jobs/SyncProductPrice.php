<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProductPrice implements ShouldQueue, ShouldBeUnique
{

    /**
     * The product instance.
     */
    protected $product;

    /**
     * Array of product data.
     */
    protected $productData;

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public $uniqueFor = 360;

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId()
    {
        return $this->product->id;
    }


    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, array $productData)
    {
        $this->product = $product;
        $this->productData = $productData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Update the product price
        $this->product->price = $this->productData['price'];
        $this->product->save();
    }
}
