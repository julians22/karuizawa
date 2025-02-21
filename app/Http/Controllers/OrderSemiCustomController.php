<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderSemiCustomController extends Controller
{
    public function index()
    {
        return view('backend.order.semi-custom.index');
    }
}
