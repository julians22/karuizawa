<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderReadyToWearController extends Controller
{

    public function index()
    {
        return view('backend.order.ready-to-wear.index');
    }

}
