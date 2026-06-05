<?php

use Illuminate\Support\Facades\Route;
use Modules\Pipedrive\Http\Controllers\PipedriveController;

Route::middleware([
    'auth',
    'permission:crm_integrations.view'
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

        Route::middleware('permission:crm_integrations.create')
            ->group(function () {

                Route::post('/', 'store')
                    ->name('store');
            });

        /*
        |--------------------------------------------------------------------------
        | Edit / Sync
        |--------------------------------------------------------------------------
        */

        Route::middleware('permission:crm_integrations.edit')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Update
                |--------------------------------------------------------------------------
                */

                Route::post('/{account}/update', 'update')
                    ->name('update');

                /*
                |--------------------------------------------------------------------------
                | Connect
                |--------------------------------------------------------------------------
                */

                Route::get('/{account}/connect', 'connect')
                    ->name('connect');

                /*
                |--------------------------------------------------------------------------
                | Details
                |--------------------------------------------------------------------------
                */

                Route::get('/{account}/details', 'details')
                    ->name('details');

                /*
                |--------------------------------------------------------------------------
                | Pipelines
                |--------------------------------------------------------------------------
                */

                Route::get('/{account}/pipelines', 'pipelines')
                    ->name('pipelines');

                /*
                |--------------------------------------------------------------------------
                | Sync Fields
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/{account}/sync/fields',
                    'syncFields'
                )->name('sync.fields');

                /*
                |--------------------------------------------------------------------------
                | Sync Stages
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/{account}/sync/stages',
                    'syncStages'
                )->name('sync.stages');

                Route::delete('/{account}', 'destroy')
                    ->name('destroy');

            });
    });
