<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SemiCustomLightJacketController extends Controller
{
    public function index()
    {
        return view('frontend.user.semi-custom-light-jacket', [
            'dataSemiCustomLightJacket' => collect(config('karuizawa-light-jacket-master'))
        ]);
    }
}
