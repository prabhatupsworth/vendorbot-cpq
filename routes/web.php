<?php

use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\Settings\PipeDrive\PipedriveController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Not Logged In)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Signup (Register)
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');


    Route::get('/security', [ProfileController::class, 'security'])->name('security');
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | User Module
    |--------------------------------------------------------------------------
    */

    Route::prefix('users')->name('users.')->group(function () {

        Route::get('/', [UserController::class, 'index'])
            ->middleware('permission:users.view')
            ->name('index');

        Route::get('/create', [UserController::class, 'create'])
            ->middleware('permission:users.create')
            ->name('create');

        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:users.create')
            ->name('store');

        Route::get('/{id}/edit', [UserController::class, 'edit'])
            ->middleware('permission:users.edit')
            ->name('edit');

        Route::put('/{id}', [UserController::class, 'update'])
            ->middleware('permission:users.edit')
            ->name('update');

        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::prefix('settings/pipedrive')
        ->name('settings.pipedrive.')
        ->middleware('permission:pipedrive.view')
        ->group(function () {

            Route::get('/', [PipedriveController::class, 'index'])
                ->name('index');

            Route::middleware('permission:pipedrive.create')->group(function () {
                Route::post('/', [PipedriveController::class, 'store'])
                    ->name('store');
            });

            Route::middleware('permission:pipedrive.edit')->group(function () {
                Route::get('/connect/{id}', [PipedriveController::class, 'connect'])
                    ->name('connect');

                Route::get('/{id}/details', [PipedriveController::class, 'details'])
                    ->name('details');

                Route::post('/{id}/sync-stages', [PipedriveController::class, 'syncStages'])
                    ->name('sync.stages');

                Route::post('/{id}/sync-fields', [PipedriveController::class, 'syncFields'])
                    ->name('sync.fields');
            });
        });
    /*
    |--------------------------------------------------------------------------
    | Role Module (Super Admin Only)
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:super_admin')->prefix('roles')->group(function () {

        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/data', [RoleController::class, 'getRoles'])->name('roles.data');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/', [RoleController::class, 'store'])->name('roles.store');

        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::post('/{id}', [RoleController::class, 'update'])->name('roles.update');


        Route::get('/{id}/permissions', [RoleController::class, 'permissions'])
            ->name('roles.permissions');

        Route::post('/{id}/permissions', [RoleController::class, 'updatePermissions'])
            ->name('roles.update-permissions');
        Route::get('/{id}/permission-data', [RoleController::class, 'permissionData'])->name('roles.permission-data');
        Route::post('/{id}/toggle-permission', [RoleController::class, 'togglePermission'])->name('roles.toggle-permission');
    });
});
