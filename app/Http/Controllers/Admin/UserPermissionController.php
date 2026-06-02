<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    public function permissions($id)
    {
        $user = User::with('roles')->findOrFail($id);

        $modules = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });

        $userPermissions = $user
            ->getAllPermissions()
            ->pluck('name')
            ->toArray();

        return view(
            'users.permissions',
            compact(
                'user',
                'modules',
                'userPermissions'
            )
        );
    }

   public function togglePermission(Request $request, $id)
{
    $user = User::findOrFail($id);

    $permission = Permission::where(
        'name',
        $request->permission
    )->first();

    if (!$permission) {
        return response()->json([
            'status' => false,
            'message' => 'Permission not found.'
        ]);
    }

    if ((int) $request->checked === 1) {

        $user->givePermissionTo($permission->name);

    } else {

        $user->revokePermissionTo($permission->name);
    }

    app(\Spatie\Permission\PermissionRegistrar::class)
        ->forgetCachedPermissions();

    return response()->json([
        'status' => true,
        'message' => 'Permission updated successfully.'
    ]);
}
}
