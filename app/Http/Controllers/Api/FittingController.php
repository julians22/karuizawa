<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderHistoryResource;

class FittingController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with([
            'orderItems',
        ])
        ->whereHas('orderItems', function ($query) {
            $query->where('product_type', 'App\Models\SemiCustomProduct');
        })
        ->when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        })
        ->when($request->date, function ($query) use ($request) {
            $date = Carbon::parse($request->date);
            return $query->whereDate('created_at', $date);
        })
        ->when($request->keyword, function ($query) use ($request) {
            // we can search through customer name, email, phone
            return $query->where('id', 'like', "%$request->keyword%")
                ->orWhereHas('customer', function ($query) use ($request) {
                    $query->where('full_name', 'like', "%$request->keyword%")
                        ->orWhere('email', 'like', "%$request->keyword%")
                        ->orWhere('phone', 'like', "%$request->keyword%");
                });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return response()->json(
            OrderHistoryResource::collection($orders)
        );
    }
}
