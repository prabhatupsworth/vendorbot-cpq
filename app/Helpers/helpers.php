<?php

use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Facades\Module;

use App\Models\Currency;
use Modules\Project\Models\Project;

use App\Models\PermissionOverride;
use Spatie\Permission\Models\Permission;

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
        $user = Auth::user()?->fresh();
        if (! $user) {
            return null;
        }
        if ($user->current_project_id) {
            return $user->current_project_id;
        }

        $projectId = $user->projects()->value('projects.id');

        if ($projectId) {

            $user->update([
                'current_project_id' => $projectId
            ]);

            return $projectId;
        }

        return Auth::user()?->fresh()?->current_project_id;
    }
}



if (!function_exists('active_currency_code')) {

    function active_currency_code()
    {
        $project = Project::find(current_project_id());

        return $project?->currency_code ?? 'EUR';
    }
}

if (!function_exists('currency')) {

    function currency($amount, $currencyCode = null)
    {
        $currencyCode = $currencyCode ?? active_currency_code();

        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            return number_format($amount, 2);
        }

        return $currency->symbol . ' ' . number_format($amount, 2);
    }
}

if (!function_exists('active_currency_symbol')) {

    function active_currency_symbol()
    {
        $currency = Currency::where('code', active_currency_code())->first();

        return $currency?->symbol ?? '€';
    }
}


if (!function_exists('userCan')) {

    function userCan($permission)
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Deny override
        $denied = PermissionOverride::where([
            'user_id' => $user->id,
            'permission_name' => $permission,
            'is_denied' => true
        ])->exists();

        if ($denied) {
            return false;
        }

        // Role + Direct permissions
        return $user->hasPermissionTo($permission);
    }
}



if (!function_exists('userCanModule')) {

    function userCanModule($module)
    {
        $permissions = Permission::where(
            'name',
            'like',
            $module . '.%'
        )->pluck('name');

        foreach ($permissions as $permission) {

            if (userCan($permission)) {
                return true;
            }
        }

        return false;
    }
}
