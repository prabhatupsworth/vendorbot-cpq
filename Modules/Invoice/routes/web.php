<?php

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Http\Controllers\Lexware\LexwareController;

Route::middleware('permission:invoice_management.view')
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

        Route::get('/{account}/test-connection', 'testConnection')
            ->name('test-connection');

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Route::middleware('permission:invoice_management.create')
            ->group(function () {

                Route::post('/store', 'store')
                    ->name('store');
            });

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        Route::middleware('permission:invoice_management.edit')
            ->group(function () {

                Route::post('/{account}/update', 'update')
                    ->name('update');
            });


        Route::middleware('permission:invoice_management.delete')
            ->group(function () {

                Route::delete('/{account}/delete', 'destroy')
                    ->name('destroy');
            });
    });
