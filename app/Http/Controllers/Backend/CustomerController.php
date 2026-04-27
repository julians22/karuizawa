<?php

namespace App\Http\Controllers\Backend;

use App\Exports\CustomerData;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SemiCustomProduct;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {

        $customersQuery = Customer::select('id', 'created_at');

        $totalCustomer = cache()->remember('totalCustomer', 60 * 60, function () use ($customersQuery) {
            return $customersQuery->count();
        });

        $newCustomer = cache()->remember('newCustomer', 60 * 60, function () use ($customersQuery) {
            return $customersQuery->where('created_at', '>=', now()->subDays(30))->count();
        });

        $repeatCustomer = cache()->remember('repeatCustomer', 60 * 60, function () {
            return SemiCustomProduct::select('customer_id', 'created_at')
                ->where('created_at', '>=', now()->subDays(30))
                ->where('size->order', '2. REPEAT ORDER')
                ->get()
                ->groupBy('customer_id')
                ->count();
        });

        return view('backend.customer.index', compact('totalCustomer', 'newCustomer', 'repeatCustomer'));
    }

    public function export() {
        return (new CustomerData)->download('all_customer.xlsx');
    }

    public function show() {
        return view('backend.customer.show');
    }

    public function orders(Request $request, Customer $customer) {

        $ordersQuery = $customer->orders()->select('id', 'status', 'total_price', 'created_at');

        $totalOrders = cache()->remember("customer_{$customer->id}_total_orders", 60 * 60, function () use ($ordersQuery) {
            return $ordersQuery->count();
        });

        $completedOrders = cache()->remember("customer_{$customer->id}_completed_orders", 60 * 60, function () use ($ordersQuery) {
            return $ordersQuery->where('status', 'completed')->count();
        });

        $totalSpending = cache()->remember("customer_{$customer->id}_total_spending", 60 * 60, function () use ($ordersQuery) {
            return $ordersQuery->where('status', 'completed')->sum('total_price');
        });

        $currentMonthSpending = cache()->remember("customer_{$customer->id}_current_month_spending", 60 * 60, function () use ($ordersQuery) {
            return $ordersQuery->where('status', 'completed')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('total_price');
        });

        $stats = [
            [
                'label' => 'Total Orders',
                'value' => $totalOrders,
            ],
            [
                'label' => 'Completed Orders',
                'value' => $completedOrders,
            ],
            [
                'label' => 'Total Spending',
                'value' => price_format($totalSpending),
            ],
            [
                'label' => 'Current Month Spending',
                'value' => price_format($currentMonthSpending),
            ]
        ];

        return view('backend.customer.orders', compact('customer', 'stats'));
    }

    public function edit() {
        return view('backend.customer.edit');
    }

    public function update() {
        return redirect()->route('admin.customer.index');
    }
}
