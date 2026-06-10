<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupon\Http\Controllers\CouponController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('coupons', CouponController::class)->names('coupon');
    Route::post('/coupon/generate-code', [CouponController::class, 'generateCode'])->name('coupon.generate-code');
});
