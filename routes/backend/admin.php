<?php

use App\Http\Controllers\Backend\PromoControler;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductCategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\StoreController;
use App\Http\Controllers\Backend\SystemInformationController;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Store;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });

Route::group(['prefix' => 'customer', 'as' => 'customer.'], function() {
    Route::get('/', [CustomerController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Customer'), route('admin.customer.index'));
        });

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

        Route::patch('/', [CustomerController::class, 'update'])->name('update');

    });
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

    Route::group(['prefix' => '{store}'], function() {
        Route::get('edit', [StoreController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Store $store) {
                $trail->parent('admin.store.index');
                $trail->push(__('Edit'), route('admin.store.edit', $store));
            });
        Route::patch('/', [StoreController::class, 'update'])->name('update');

        Route::delete('/', [StoreController::class, 'destroy'])->name('destroy');
    });

    Route::post('/', [StoreController::class, 'store'])->name('store');
});

Route::prefix('promos')->group(function () {
    Route::get('/', [PromoControler::class, 'index'])
        ->name('promo.index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('promos'), route('admin.promo.index'));
        });
    Route::get('create', [PromoControler::class, 'create'])
        ->name('promo.create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.promo.index');
            $trail->push(__('Create'), route('admin.promo.create'));
        });

    Route::post('/', [PromoControler::class, 'store'])->name('promo.store');

    Route::group(['prefix' => '{promo}'], function() {
        Route::get('edit', [PromoControler::class, 'edit'])
            ->name('promo.edit')
            ->breadcrumbs(function (Trail $trail, Promo $promo) {
                $trail->parent('admin.promo.index');
                $trail->push(__('Edit'), route('admin.promo.edit', $promo));
            });

        Route::patch('/', [PromoControler::class, 'update'])->name('promo.update');
        Route::delete('/', [PromoControler::class, 'destroy'])->name('promo.destroy');
    });
});

// Category Routes
Route::group(['prefix' => 'product-category', 'as' => 'product-category.'], function() {
    Route::get('/', [ProductCategoryController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('Product Category'), route('admin.product-category.index'));
        });

    Route::group(['prefix' => '{category}'], function() {
        Route::get('edit', [ProductCategoryController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, $category) {
                $trail->parent('admin.product-category.index');
                $trail->push(__('Edit'), route('admin.product-category.edit', $category));
            });

        Route::patch('/', [ProductCategoryController::class, 'update'])->name('update');
        Route::delete('/', [ProductCategoryController::class, 'destroy'])->name('destroy');
    });
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

    Route::get('fetch-stock', [ProductController::class, 'fetchStock'])
        ->name('fetch-stock');

    Route::get('fetch-price', [ProductController::class, 'fetchPrice'])
        ->name('fetch-price');

    Route::group(['prefix' => '{product}'], function() {

        Route::get('/', [ProductController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index');
                $trail->push(__('Show'), route('admin.product.show', $product));
            });


        Route::get('edit', [ProductController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Product $product) {
                $trail->parent('admin.product.index');
                $trail->push(__('Edit'), route('admin.product.edit', $product));
            });

        Route::patch('/', [ProductController::class, 'update'])->name('update');
        Route::delete('/', [ProductController::class, 'destroy'])->name('destroy');
    });
});


Route::group(['prefix' => 'system-information', 'as' => 'system-information.'], function() {
    Route::get('/', [SystemInformationController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->push(__('System Information'), route('admin.system-information.index'));
        });

    Route::get('reload-auth-cache', [SystemInformationController::class, 'reloadAuthCache'])
        ->name('reload-auth-cache');

    Route::get('accurate', [SystemInformationController::class, 'accurate'])
        ->name('accurate');
});
