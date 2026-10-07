<?php

use App\Models\Customer;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\FittingController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Catalog\ProductController as CatalogProductController;
use App\Http\Controllers\Api\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Api\Customer\AddressController as CustomerAddressController;
use App\Http\Controllers\Api\OnlineSales\CheckoutController as OnlineSalesCheckoutController;
use App\Http\Controllers\Api\OnlineSales\ShippingController as OnlineSalesShippingController;
use App\Http\Controllers\Api\OnlineSales\WebhookController as OnlineSalesWebhookController;
use App\Http\Controllers\Frontend\User\SemiCustomController;

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

// Public catalog for online/frontend consumption. Products are restricted to
// categories enabled in config('catalog.online_categories') and always
// reflect live stock from product_actual_stocks.
Route::prefix('v1/catalog')->middleware('throttle:60,1')->group(function () {
    Route::get('products', [CatalogProductController::class, 'index']);
    Route::get('products/{sku}', [CatalogProductController::class, 'show']);
});

// Online Sales checkout + Biteship shipping integration.
// Checkout requires an authenticated customer (Sanctum token) so orders are always tied to the logged-in user.
Route::prefix('v1/online-sales')->middleware('throttle:60,1')->group(function () {
    Route::post('shipping/rates', [OnlineSalesShippingController::class, 'rates']);
    Route::get('shipping/areas', [OnlineSalesShippingController::class, 'areas']);

    Route::middleware(['auth:sanctum', 'ensure.customer'])->group(function () {
        Route::post('checkout', [OnlineSalesCheckoutController::class, 'store']);
    });
});

// Biteship webhook callback (order/tracking status updates). Authenticated via a shared token, not sanctum.
Route::post('v1/online-sales/webhooks/biteship', [OnlineSalesWebhookController::class, 'biteship']);

// Payment confirmation callback from the Kizoku frontend (Midtrans notification relay).
// Authenticated via a shared token (services.online_sales.payment_token), not sanctum.
Route::post('v1/online-sales/payments/confirm', [\App\Http\Controllers\Api\OnlineSales\PaymentController::class, 'confirm']);

Route::group(['prefix' => 'customer/auth'], function () {
    Route::post('register/otp/request', [CustomerAuthController::class, 'requestRegisterOtp']);
    Route::post('register', [CustomerAuthController::class, 'register']);

    Route::post('set-password/otp/request', [CustomerAuthController::class, 'requestSetPasswordOtp']);
    Route::post('set-password', [CustomerAuthController::class, 'setPassword']);

    Route::post('login/otp/request', [CustomerAuthController::class, 'requestLoginOtp']);
    Route::post('login', [CustomerAuthController::class, 'login']);
    Route::post('token', [CustomerAuthController::class, 'issueToken']);

    Route::post('reset-password/otp/request', [CustomerAuthController::class, 'requestResetPasswordOtp']);
    Route::post('reset-password', [CustomerAuthController::class, 'resetPassword']);

    Route::middleware(['auth:sanctum', 'ensure.customer'])->group(function () {
        Route::get('me', [CustomerAuthController::class, 'me']);
        Route::post('logout', [CustomerAuthController::class, 'logout']);
        Route::get('orders', [\App\Http\Controllers\Api\Customer\OrderController::class, 'index']);
    });
});

Route::group(['prefix' => 'customer/addresses', 'middleware' => ['auth:sanctum', 'ensure.customer']], function () {
    Route::get('/', [CustomerAddressController::class, 'index']);
    Route::post('/', [CustomerAddressController::class, 'store']);
    Route::get('{address}', [CustomerAddressController::class, 'show']);
    Route::patch('{address}', [CustomerAddressController::class, 'update']);
    Route::delete('{address}', [CustomerAddressController::class, 'destroy']);
    Route::post('{address}/primary', [CustomerAddressController::class, 'setPrimary']);
});

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
            'id' => 'nullable|exists:customers,id',
            'first_name' => 'required',
            'phone' => 'required|min:10',
            'email' => 'required|email',
            'address' => 'nullable|string',
            'is_male' => 'required|boolean',
        ]);

        if ($request->id) {
            $customer = Customer::find($request->id);
            $customer->full_name = $request->first_name;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->address = $request->address;
            $customer->is_male = (bool) $request->is_male;
            $customer->save();

            return response()->json([
                'success' => true,
                'data' => $customer,
            ], 200);
        }

        $emailUnique = \App\Models\Customer::where('email', $request->email)->first();
        if ($emailUnique) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exists',
            ], 422);
        }

        try {
            $customer = Customer::create([
                'full_name' => $request->first_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'is_male' => (bool) $request->is_male
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
Route::post('set-handling-date', [OrderController::class, 'set_handling_date']);
Route::post('set-status', [OrderController::class, 'set_status']);

Route::get('fitting-orders', [FittingController::class, 'index']);

Route::post('store-order', [OrderController::class, 'store']);

Route::post('send-payment', [OrderController::class, 'store_payment']);

Route::post('semi-custom/submit', [SemiCustomController::class, 'submit']);

Route::post('semi-custom/customer-size/{id}', [SemiCustomController::class, 'findCustomerSize']);

Route::group(['prefix' => 'profile', 'as' => 'profile.'], function() {
    Route::post('update', [ProfileController::class, 'update'])->name('update');
});
