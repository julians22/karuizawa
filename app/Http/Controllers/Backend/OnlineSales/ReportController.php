<?php

namespace App\Http\Controllers\Backend\OnlineSales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled('from') ? Carbon::parse($request->from) : now()->subDays(30);
        $to = $request->filled('to') ? Carbon::parse($request->to) : now();

        $baseQuery = Order::online()->whereBetween('order_date', [$from->startOfDay(), $to->endOfDay()]);

        $totalOrders = (clone $baseQuery)->count();
        $totalRevenue = (clone $baseQuery)->where('status', config('enums.order_status.completed'))->sum('total_price');
        $pendingOrders = (clone $baseQuery)->where('status', config('enums.order_status.pending'))->count();
        $cancelledOrders = (clone $baseQuery)->where('status', config('enums.order_status.cancelled'))->count();

        $ordersByStatus = (clone $baseQuery)
            ->selectRaw('status, count(*) as total, sum(total_price) as revenue')
            ->groupBy('status')
            ->get();

        $ordersByCourier = (clone $baseQuery)
            ->join('order_shipments', 'order_shipments.order_id', '=', 'orders.id')
            ->selectRaw('order_shipments.courier_company, count(*) as total')
            ->groupBy('order_shipments.courier_company')
            ->get();

        $dailySales = (clone $baseQuery)
            ->where('status', config('enums.order_status.completed'))
            ->selectRaw('DATE(order_date) as date, sum(total_price) as revenue, count(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('backend.online-sales.report.index', compact(
            'from',
            'to',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'cancelledOrders',
            'ordersByStatus',
            'ordersByCourier',
            'dailySales'
        ));
    }
}
