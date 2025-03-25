<?php

use App\Http\Controllers\Backend\DashboardController;
use Tabuna\Breadcrumbs\Trail;

Route::group(['prefix' => 'report', 'as' => 'report.'], function () {

    Route::get('/', [DashboardController::class, 'report'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Report'), route('admin.report.index'));
        });

    Route::get('performance', [DashboardController::class, 'performance'])
        ->name('performance')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.report.index');
            $trail->push(__('Performance'), route('admin.report.performance'));
        });


});
