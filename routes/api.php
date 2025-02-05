<?php

use App\Models\Customer;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\FittingController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Frontend\User\SemiCustomConteroller;

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
        $keyword = $request->keyword;

        $data = \App\Models\Customer::where('id', '!=', 1)
            ->where(function ($query) use ($keyword) {
            $query->where('full_name', 'like', '%' . $keyword . '%')
                ->orWhere('phone', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        })
        ->orderBy('full_name', 'asc')
        ->paginate(20);

        return $data;
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
            'phone' => 'required|min:10',
            'email' => 'required|email',
            'is_male' => 'required|boolean',
        ]);

        try {
            $customer = Customer::firstOrCreate([
                'email' => $request->email
            ], [
                'full_name' => $request->first_name,
                'phone' => $request->phone,
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

Route::get('orders', [OrderController::class, 'index']);
Route::get('incoming-orders', [OrderController::class, 'incoming_order']);
Route::get('order/{id}', [OrderController::class, 'show']);
Route::post('set-handling-date/{id}', [OrderController::class, 'set_handling_date']);

Route::get('fitting-orders', [FittingController::class, 'index']);

Route::post('store-order', [OrderController::class, 'store']);

Route::post('send-payment', [OrderController::class, 'store_payment']);

Route::post('semi-custom/submit', [SemiCustomConteroller::class, 'submit']);

Route::group(['prefix' => 'profile', 'as' => 'profile.'], function() {
    Route::post('update', [ProfileController::class, 'update'])->name('update');
});
