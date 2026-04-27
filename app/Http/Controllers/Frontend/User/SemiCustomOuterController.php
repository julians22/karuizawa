<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SemiCustomOuterController extends Controller
{
    public function index()
    {
        return view('frontend.user.semi-custom-outer', [
            'dataSemiCustomOuter' => collect(config('karuizawa-outer-shirt-master'))
        ]);
    }

    public function submit(Request $request)
    {
        $data = $request->all();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
