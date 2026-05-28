<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'web',
    'auth'
])
    ->prefix('products')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Import Product
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/import',
            [ProductController::class, 'import']
        )->name('products.import');

        Route::post(
            '/import-product',
            [ProductController::class, 'importProduct']
        )->name('products.import-product');

        /*
        |--------------------------------------------------------------------------
        | Resource Routes
        |--------------------------------------------------------------------------
        */

        Route::resource(
            '/',
            ProductController::class
        )
            ->parameters([
                '' => 'product'
            ])
            ->names('products');
    });
