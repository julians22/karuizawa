<?php

use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\OrderReadyToWearController;
use App\Http\Controllers\OrderSemiCustomController;
use App\Models\Order;
use App\Models\OrderItem;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'order', 'as' => 'order.'], function() {
    Route::get('/', [OrderController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Order'), route('admin.order.index'));
        });

    Route::group(['prefix' => 'semi-custom', 'as' => 'semi-custom.'], function() {

        Route::get('/', [OrderSemiCustomController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.order.index');
                $trail->push(__('Semi Custom'), route('admin.order.semi-custom.index'));
            });

        Route::group(['prefix' => '{semiCustomProduct}'], function() {
            Route::patch('approve', [OrderSemiCustomController::class, 'approve'])
                ->name('approve');

            Route::patch('cancel', [OrderSemiCustomController::class, 'cancel'])
                ->name('cancel');
        });
    });


    Route::group(['prefix' => 'ready-to-wear', 'as' => 'ready-to-wear.'], function() {

        Route::get('/', [OrderReadyToWearController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->parent('admin.order.index');
                $trail->push(__('Ready to Wear'), route('admin.order.ready-to-wear.index'));
            });
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

        Route::get('upload-accurate', [OrderController::class, 'uploadAccurate'])->name('upload-accurate');

        Route::patch('/', [OrderController::class, 'update'])->name('update');
        Route::delete('/', [OrderController::class, 'destroy'])->name('destroy');

        Route::patch('approve', [OrderController::class, 'approve'])->name('approve');
        Route::patch('cancel', [OrderController::class, 'cancel'])->name('cancel');

        Route::patch('unsync', [OrderController::class, 'unsync'])->name('unsync');
    });
});
