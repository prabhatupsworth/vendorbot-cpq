<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupon\Http\Controllers\CouponController;

Route::resource('coupons', CouponController::class)->names('coupon');
