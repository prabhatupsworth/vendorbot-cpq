<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PermissionOverride;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UserPermissionController extends Controller
{
    public function permissions($id)
    {
        $user = User::with('roles')->findOrFail($id);

        $modules = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });

        $allPermissions = $user
            ->getAllPermissions()
            ->pluck('name')
            ->toArray();

        $deniedPermissions = PermissionOverride::where(
            'user_id',
            $user->id
        )
            ->where('is_denied', true)
            ->pluck('permission_name')
            ->toArray();

        $userPermissions = array_values(
            array_diff(
                $allPermissions,
                $deniedPermissions
            )
        );

        return view(
            'users.permissions',
            compact(
                'user',
                'modules',
                'userPermissions',
                'deniedPermissions'
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

            // Remove deny override
            PermissionOverride::where([
                'user_id' => $user->id,
                'permission_name' => $permission->name
            ])->delete();

            // Give direct permission
            if (!$user->hasDirectPermission($permission->name)) {
                $user->givePermissionTo($permission->name);
            }
        } else {

            // Remove direct permission
            if ($user->hasDirectPermission($permission->name)) {
                $user->revokePermissionTo($permission->name);
            }

            // Create deny override$userPermissions = $user
            PermissionOverride::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'permission_name' => $permission->name
                ],
                [
                    'is_denied' => true
                ]
            );
        }

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        return response()->json([
            'status' => true,
            'message' => 'Permission updated successfully.'
        ]);
    }
}
