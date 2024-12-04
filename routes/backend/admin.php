<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\StoreController;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });

Route::group(['prefix' => 'store', 'as' => 'store.'], function() {
    Route::get('/', [StoreController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Store'), route('admin.store.index'));
        });

    Route::get('create', [StoreController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.store.index');
            $trail->push(__('Create'), route('admin.store.create'));
        });

    Route::post('/', [StoreController::class, 'store'])->name('store');
});

// Product Routes
Route::group(['prefix' => 'product', 'as' => 'product.'], function() {
    Route::get('/', [ProductController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Product'), route('admin.product.index'));
        });

    Route::get('create', [ProductController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.product.index');
            $trail->push(__('Create'), route('admin.product.create'));
        });

    Route::post('/', [ProductController::class, 'store'])->name('store');
});
