<?php

use Illuminate\Support\Facades\Route;
use Modules\Pipedrive\Http\Controllers\PipedriveController;

Route::middleware([
    'auth',
    'permission:pipedrive.view'
])
    ->prefix('settings/pipedrive')
    ->name('settings.pipedrive.')
    ->controller(PipedriveController::class)
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Index
        |--------------------------------------------------------------------------
        */

        Route::get('/', 'index')
            ->name('index');

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Route::middleware('permission:pipedrive.create')
            ->group(function () {

                Route::post('/', 'store')
                    ->name('store');
            });

        /*
        |--------------------------------------------------------------------------
        | Edit / Sync
        |--------------------------------------------------------------------------
        */

        Route::middleware('permission:pipedrive.edit')
            ->group(function () {

                Route::post('/{account}/update', 'update')
                    ->name('update');

                Route::get('/{account}/connect', 'connect')
                    ->name('connect');

                Route::get('/{account}/details', 'details')
                    ->name('details');

                Route::get('/{account}/pipelines', 'pipelines')
                    ->name('pipelines');

                /*
                |--------------------------------------------------------------------------
                | Sync
                |--------------------------------------------------------------------------
                */

                Route::prefix('{account}/sync')
                    ->name('sync.')
                    ->group(function () {

                        Route::post('/stages', 'syncStages')
                            ->name('stages');

                        Route::post('/fields', 'syncFields')
                            ->name('fields');
                    });
            });
    });
