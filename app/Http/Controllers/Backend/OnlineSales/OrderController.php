<?php

namespace App\Http\Controllers\Backend\OnlineSales;

use App\Http\Controllers\Controller;
use App\Jobs\CreateBiteshipShipmentJob;
use App\Models\Order;
use App\Services\Shipping\BiteshipService;

class OrderController extends Controller
{
    public function index()
    {
        return view('backend.online-sales.order.index');
    }

    public function show(Order $order)
    {
        $order->load('orderItems.product', 'customer', 'store', 'shipment.histories', 'shipment.customerAddress');

        return view('backend.online-sales.order.show', compact('order'));
    }

    public function retryShipment(Order $order)
    {
        $order->load('shipment');

        if (!$order->shipment) {
            return back()->withFlashDanger(__('This order has no shipment record.'));
        }

        if ($order->shipment->biteship_order_id) {
            return back()->withFlashDanger(__('Shipment already created on Biteship.'));
        }

        CreateBiteshipShipmentJob::dispatch($order->shipment->id);

        return back()->withFlashSuccess(__('Shipment creation has been queued.'));
    }

    public function trackShipment(Order $order, BiteshipService $biteship)
    {
        $order->load('shipment');

        if (!$order->shipment || !$order->shipment->waybill_id) {
            return back()->withFlashDanger(__('No waybill number available yet for this order.'));
        }

        $courierCode = $order->shipment->courier_company;
        $tracking = $biteship->trackOrder($order->shipment->waybill_id, $courierCode);

        return back()->with('tracking', $tracking);
    }
}
