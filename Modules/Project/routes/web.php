<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\Http\Controllers\DashboardController;
use Modules\Project\Http\Controllers\ProjectController;
use Modules\Project\Http\Controllers\ProjectCompanyDetailController;
use Modules\Project\Http\Controllers\ProjectFieldMappingController;
use Modules\Project\Http\Controllers\ProjectGeoFilterController;
use Modules\Project\Http\Controllers\ProjectSmtpController;
use Modules\Project\Http\Controllers\ProjectStageActionController;
use Modules\Project\Http\Controllers\ProjectUserController;

Route::prefix('projects')
    ->name('projects.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Project CRUD
        |--------------------------------------------------------------------------
        */

        Route::controller(DashboardController::class)->group(
            function () {
                Route::get('/dashboard', 'index');
            }
        );

        Route::controller(ProjectController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->middleware('permission:projects.view')
                    ->name('index');

                Route::post('/store', 'store')
                    ->middleware('permission:projects.create')
                    ->name('store');

                Route::get('/{project}/edit', 'edit')
                    ->middleware('permission:projects.edit')
                    ->name('edit');

                Route::put('/{project}/update', 'update')
                    ->middleware('permission:projects.edit')
                    ->name('update');

                Route::get('/{project}', 'show')
                    ->middleware('permission:projects.view')
                    ->name('show');

                Route::delete('/{project}', 'destroy')
                    ->middleware('permission:projects.delete')
                    ->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Company Details
        |--------------------------------------------------------------------------
        */

        Route::prefix('{project}/company')
            ->name('company.')
            ->controller(ProjectCompanyDetailController::class)
            ->group(function () {

                Route::post('/store', 'store')
                    ->name('store');

                Route::get('/', 'show')
                    ->name('show');

                Route::delete('/logo/{id}', 'deleteLogo')
                    ->name('logo.delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Project Users
        |--------------------------------------------------------------------------
        */

        Route::prefix('{project}/users')
            ->name('users.')
            ->controller(ProjectUserController::class)
            ->group(function () {

                Route::post('/add', 'add_user')
                    ->name('add');

                Route::delete('/{user}/remove', 'remove_user')
                    ->name('remove');
            });

        /*
        |--------------------------------------------------------------------------
        | SMTP
        |--------------------------------------------------------------------------
        */

        Route::prefix('{project}/smtp')
            ->name('smtp.')
            ->controller(ProjectSmtpController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/store', 'store')
                    ->name('store');

                Route::post('/{smtp}/test', 'testSmtp')
                    ->name('test');

                Route::get('/{smtp}', 'show')
                    ->name('show');

                Route::put('/{smtp}', 'update')
                    ->name('update');

                Route::delete('/{smtp}', 'destroy')
                    ->name('delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Geo Filter
        |--------------------------------------------------------------------------
        */

        Route::prefix('{project}/geo-filter')
            ->name('geo.')
            ->controller(ProjectGeoFilterController::class)
            ->group(function () {

                Route::post('/store', 'store')
                    ->name('store');
            });

        /*
        |--------------------------------------------------------------------------
        | Field Mapping
        |--------------------------------------------------------------------------
        */

        Route::prefix('{project}/field-mappings')
            ->name('field-mappings.')
            ->controller(ProjectFieldMappingController::class)
            ->group(function () {

                Route::post('/store', 'store')
                    ->name('store');

                Route::delete('/{mapping}', 'destroy')
                    ->name('delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Stage Actions
        |--------------------------------------------------------------------------
        */

        Route::prefix('{project}/stages')
            ->name('stages.')
            ->controller(ProjectStageActionController::class)
            ->group(function () {

                Route::post('/store', 'store')
                    ->name('store');

                Route::put('/{stageAction}', 'update')
                    ->name('update');

                Route::delete('/{stageAction}', 'destroy')
                    ->name('delete');
            });
    });
