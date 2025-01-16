<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        return view('backend.order.index');
    }

    public function show(Order $order)
    {
        return view('backend.order.show', compact('order'));
    }
}
