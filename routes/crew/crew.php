<?php

use App\Http\Controllers\Crew\DashboardController;
use App\Http\Controllers\Frontend\HomeController;
use Tabuna\Breadcrumbs\Trail;

Route::get('/', [HomeController::class, 'index'])
    ->name('index')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('frontend.index'));
    });

Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('frontend.index')
            ->push(__('Dashboard'), route('frontend.dashboard'));
    });
