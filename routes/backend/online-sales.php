<?php

use App\Http\Controllers\Backend\OnlineSales\OrderController;
use App\Http\Controllers\Backend\OnlineSales\ReportController;
use App\Models\Order;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'online-sales', 'as' => 'online-sales.'], function () {

    Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
        Route::get('/', [ReportController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->push(__('Online Sales Reporting'), route('admin.online-sales.report.index'));
            });
    });

    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::get('/', [OrderController::class, 'index'])
            ->name('index')
            ->breadcrumbs(function (Trail $trail) {
                $trail->push(__('Online Sales Management'), route('admin.online-sales.order.index'));
            });

        Route::group(['prefix' => '{order}'], function () {
            Route::get('/', [OrderController::class, 'show'])
                ->name('show')
                ->breadcrumbs(function (Trail $trail, Order $order) {
                    $trail->parent('admin.online-sales.order.index');
                    $trail->push(__('Show'), route('admin.online-sales.order.show', $order));
                });

            Route::post('retry-shipment', [OrderController::class, 'retryShipment'])->name('retry-shipment');
            Route::post('track-shipment', [OrderController::class, 'trackShipment'])->name('track-shipment');
        });
    });
});
