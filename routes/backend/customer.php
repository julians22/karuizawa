<?php

use App\Http\Controllers\Backend\CustomerController;
use App\Models\Customer;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'customer', 'as' => 'customer.'], function() {
    Route::get('/', [CustomerController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Customer'), route('admin.customer.index'));
        });

    Route::get('export', [CustomerController::class, 'export'])
        ->name('export');

    Route::group(['prefix' => '{customer}'], function() {
        Route::get('/', [CustomerController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, Customer $customer) {
                $trail->parent('admin.customer.index');
                $trail->push(__('Show'), route('admin.customer.show', $customer));
            });

        Route::get('edit', [CustomerController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Customer $customer) {
                $trail->parent('admin.customer.index');
                $trail->push(__('Edit'), route('admin.customer.edit', $customer));
            });

        Route::get('orders', [CustomerController::class, 'orders'])
            ->name('orders')
            ->breadcrumbs(function (Trail $trail, Customer $customer) {
                $trail->parent('admin.customer.index');
                $trail->push(__('Orders'), route('admin.customer.orders', $customer));
            });

        Route::patch('/', [CustomerController::class, 'update'])->name('update');

    });
});
