<?php

use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Facades\Module;

use App\Models\Currency;

if (!function_exists('user_status_badge')) {
    function user_status_badge(int|string|null $status): string
    {
        return match ($status) {
            1 => '<span class="badge bg-success">Active</span>',
            0 => '<span class="badge bg-danger">Inactive</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}


if (!function_exists('moduleEnabled')) {

    function moduleEnabled(string $module): bool
    {
        return Module::isEnabled($module);
    }
}


if (! function_exists('current_project_id')) {

    function current_project_id()
    {
        return
            Auth::user()?->current_project_id;
    }
}




if (!function_exists('currency')) {

    function currency($amount, $currencyCode = null)
    {
        $currencyCode = $currencyCode ?? session('currency_code', 'EUR');

        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            return number_format($amount, 2);
        }

        return $currency->symbol . ' ' . number_format($amount, 2);
    }
}
