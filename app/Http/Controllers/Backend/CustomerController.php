<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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

        return view('backend.customer.index', compact('totalCustomer', 'newCustomer'));
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
