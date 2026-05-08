<?php

use Illuminate\Support\Facades\Route;
use Modules\Pipedrive\Http\Controllers\PipedriveController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pipedrives', PipedriveController::class)->names('pipedrive');
});
