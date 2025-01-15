<?php

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
Route::post('customer-store', function (Request $request) {
    $request->validate([
        'first_name' => 'required',
        'phone' => 'required',
        'email' => 'required',
        'is_male' => 'required',
    ]);

    try {
        $customer = \App\Models\Customer::create([
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

