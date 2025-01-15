<?php

use App\Http\Controllers\Backend\OrderController;
use App\Models\Order;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'order', 'as' => 'order.'], function() {
    Route::get('/', [OrderController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Order'), route('admin.order.index'));
        });

    Route::get('create', [OrderController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.order.index');
            $trail->push(__('Create'), route('admin.order.create'));
        });

    Route::post('/', [OrderController::class, 'store'])->name('store');

    Route::group(['prefix' => '{order}'], function() {

        Route::get('/', [OrderController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.index');
                $trail->push(__('Show'), route('admin.order.show', $order));
            });

        Route::get('edit', [OrderController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Order $order) {
                $trail->parent('admin.order.index');
                $trail->push(__('Edit'), route('admin.order.edit', $order));
            });

        Route::patch('/', [OrderController::class, 'update'])->name('update');
        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');
    });
});
