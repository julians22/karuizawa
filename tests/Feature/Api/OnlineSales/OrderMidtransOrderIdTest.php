<?php

namespace Tests\Feature\Api\OnlineSales;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

class OrderMidtransOrderIdTest extends TestCase
{
    public function test_midtrans_order_id_is_mass_assignable(): void
    {
        $fillable = (new \ReflectionClass(Order::class))->getDefaultProperties()['fillable'];

        $this->assertContains('midtrans_order_id', $fillable);
    }
}
