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

    public function edit() {
        return view('backend.customer.edit');
    }

    public function update() {
        return redirect()->route('admin.customer.index');
    }
}
