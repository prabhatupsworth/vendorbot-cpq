<?php

use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Facades\Module;

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
