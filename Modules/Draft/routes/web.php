<?php

use Illuminate\Support\Facades\Route;
use Modules\Draft\Http\Controllers\DraftController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('drafts', DraftController::class)->names('draft');
    Route::get('drafts/{draft}/email', [DraftController::class, 'email'])->name('draft.email');
    Route::post('/drafts/{draft}/send-test', [DraftController::class, 'sendTest'])
        ->name('draft.send-test');
});
