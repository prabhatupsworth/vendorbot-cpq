<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\CategoryController;
use Modules\Product\Http\Controllers\CategoryTabController;
use Modules\Product\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Product Module Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])
    ->prefix('products')
    ->name('products.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', CategoryController::class);

        /*
        |--------------------------------------------------------------------------
        | Category Tabs
        |--------------------------------------------------------------------------
        */

        Route::resource('tabs', CategoryTabController::class);

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Route::resource('items', ProductController::class);

        /*
        |--------------------------------------------------------------------------
        | AJAX Routes
        |--------------------------------------------------------------------------
        */

        // Get tabs by category
        Route::get(
            'categories/{category}/tabs',
            [ProductController::class, 'getTabs']
        )->name('categories.tabs');

    });
