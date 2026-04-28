<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\SemiCustomProduct;
use Illuminate\Http\Request;

class OrderSemiCustomController extends Controller
{
    public function index()
    {
        return view('backend.order.semi-custom.index');
    }

    public function approve(Request $request, SemiCustomProduct $semiCustomProduct)
    {
        if ($semiCustomProduct->status == 'finished') {
            return redirect()->route('admin.order.semi-custom.index')->withFlashDanger('This semi-custom product is already finished.');
        }

        $semiCustomProduct->status = 'finish';
        $semiCustomProduct->save();

        // Example response
        return redirect()->route('admin.order.semi-custom.index')->withFlashSuccess('Order approved successfully!');
    }

    public function cancel(Request $request, SemiCustomProduct $semiCustomProduct)
    {
        // Logic to cancel the semi-custom order
        if ($semiCustomProduct->status == 'processing') {
            return redirect()->route('admin.order.semi-custom.index')->withFlashDanger('This semi-custom product is currently being processed and cannot be cancelled.');
        }

        $semiCustomProduct->status = 'processing';
        $semiCustomProduct->save();

        // Example response
        return redirect()->route('admin.order.semi-custom.index')->withFlashSuccess('Order cancelled successfully!');
    }

    public function approveOuter(Request $request, SemiCustomProduct $semiCustomOuterProduct)
    {
        if ($semiCustomOuterProduct->status == 'finished') {
            return redirect()->route('admin.order.semi-custom-outer.index')->withFlashDanger('This semi-custom outer product is already finished.');
        }

        $semiCustomOuterProduct->status = 'finish';
        $semiCustomOuterProduct->save();

        // Example response
        return redirect()->route('admin.order.semi-custom-outer.index')->withFlashSuccess('Order approved successfully!');
    }

    public function cancelOuter(Request $request, SemiCustomProduct $semiCustomOuterProduct)
    {
        // Logic to cancel the semi-custom outer order
        if ($semiCustomOuterProduct->status == 'processing') {
            return redirect()->route('admin.order.semi-custom-outer.index')->withFlashDanger('This semi-custom outer product is currently being processed and cannot be cancelled.');
        }

        $semiCustomOuterProduct->status = 'processing';
        $semiCustomOuterProduct->save();

        // Example response
        return redirect()->route('admin.order.semi-custom-outer.index')->withFlashSuccess('Order cancelled successfully!');
    }
}
