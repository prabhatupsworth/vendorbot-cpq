<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])
    ->prefix('products')
    ->group(function () {

        Route::resource('/', ProductController::class)
            ->parameters([
                '' => 'product'
            ])
            ->names('products');

    });
