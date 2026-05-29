<?php

use Illuminate\Support\Facades\Route;
use Modules\Draft\Http\Controllers\DraftController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('drafts', DraftController::class)->names('draft');
});
