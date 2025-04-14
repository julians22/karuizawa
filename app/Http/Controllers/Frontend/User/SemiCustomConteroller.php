<?php

namespace App\Http\Controllers\Frontend\User;

use File;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\SemiCustomProduct;
use App\Http\Controllers\Controller;

class SemiCustomConteroller extends Controller
{
    public function index()
    {
        return view('frontend.user.semi-custom', [
            'dataSemiCustom' => collect(config('karuizawa-master'))
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

    public function findCustomerSize($id)
    {
        $data = SemiCustomProduct::where('customer_id', $id)->latest()->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }
}
