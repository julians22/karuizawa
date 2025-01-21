<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\SemiCustomConteroller;
use App\Models\Order;
use App\Models\SemiCustomProduct;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the user has not confirmed their email
 */
Route::group(['as' => 'user.', 'middleware' => ['auth', 'password.expires', config('boilerplate.access.middleware.verified')]], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('is_user')
        ->name('dashboard')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('frontend.index')
                ->push(__('Dashboard'), route('frontend.user.dashboard'));
        });

    Route::get('account', [AccountController::class, 'index'])
        ->name('account')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('frontend.index')
                ->push(__('My Account'), route('frontend.user.account'));
        });

    Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('edit-profile', function () {
       return view('frontend.user.edit-profile');
    })->name('edit-profile');

    Route::get('ready-to-wear', function () {
        return view('frontend.user.rtw');
    })->name('rtw');

    Route::get('semi-custom', [SemiCustomConteroller::class, 'index'])->name('semi-custom');

    Route::get('cart', function () {
        return view('frontend.user.cart');
    })->name('cart');

    Route::get('order-payment/{order}', [DashboardController::class, 'payment'])->name('order-payment');


    Route::get('customer-booking', function () {
        return view('frontend.user.booking');
    })->name('booking');

    Route::get('print-semi-custom/{id}', [DashboardController::class, 'print_sc'])->name('print-semi-custom');
    Route::get('print-bill/{id}', [DashboardController::class, 'print_bill'])->name('print-bill');

});
