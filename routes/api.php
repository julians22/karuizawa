<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('products', [ProductController::class, 'index']);

Route::get('find-product/{slug}', [ProductController::class, 'findBySlug']);

// store customer


Route::group(['prefix' => 'customer'], function () {
    Route::get('search', function (Request $request) {
        $keyword = $request->search;

        $data = \App\Models\Customer::where(function ($query) use ($keyword) {
            $query->where('full_name', 'like', '%' . $keyword . '%')
                ->orWhere('phone', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');

        })->get()->toArray();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    });

    Route::get('find', function (Request $request) {
        $id = $request->id;
        $customer = \App\Models\Customer::find($id);
        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    });

    Route::post('store', function (Request $request) {
        $request->validate([
            'first_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'is_male' => 'required',
        ]);

        try {
            $customer = \App\Models\Customer::updateOrCreate([
                'id' => $request->id
            ], [
                'full_name' => $request->first_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'is_male' => (Boolean) $request->is_male
            ]);

            return response()->json([
                'success' => true,
                'data' => $customer,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    });
});

// store order
Route::post('store-order', [OrderController::class, 'store']);

