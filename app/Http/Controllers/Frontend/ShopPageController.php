<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopPageController extends Controller
{
    public function index()
    {
        $brands = Brand::all()->toJson();

        return view('frontend.user.rtw', compact('brands'));
    }
}
