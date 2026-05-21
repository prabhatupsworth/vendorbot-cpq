<?php

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Http\Controllers\Lexware\LexwareController;

Route::middleware('permission:lexware.view')
    ->prefix('settings/invoice/lexware')
    ->name('settings.invoice.lexware.')
    ->controller(LexwareController::class)
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        Route::get('/', 'index')
            ->name('index');

        Route::get('/{account}/edit', 'edit')
            ->name('edit');

        Route::get('/{account}/details', 'details')
            ->name('details');

        Route::get('/{account}/connect', 'connect')
            ->name('connect');

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Route::middleware('permission:lexware.create')
            ->group(function () {

                Route::post('/store', 'store')
                    ->name('store');
            });

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        Route::middleware('permission:lexware.edit')
            ->group(function () {

                Route::post('/{account}/update', 'update')
                    ->name('update');
            });
    });
