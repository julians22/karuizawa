<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

/*
 * Global Routes
 *
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LocaleController::class, 'change'])->name('locale.change');

/*
 * Frontend Routes
 */
Route::group(['as' => 'frontend.'], function () {
    includeRouteFiles(__DIR__.'/frontend/');
});

/*
 * Backend Routes
 *
 * These routes can only be accessed by users with type `admin`
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    includeRouteFiles(__DIR__.'/backend/');
});

/*
 * Crew Routes
 *
 * These routes can only be accessed by users with type `crew`
 */
Route::group(['prefix' => 'crew', 'as' => 'crew.', 'middleware' => 'crew'], function () {
    includeRouteFiles(__DIR__.'/crew/');
});

