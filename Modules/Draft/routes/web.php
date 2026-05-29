<?php

use Illuminate\Support\Facades\Route;
use Modules\Draft\Http\Controllers\DraftController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('drafts', DraftController::class)->names('draft');
});
