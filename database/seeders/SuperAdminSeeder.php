<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // ✅ Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Create role
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);

        // ✅ Create user
        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
            ]
        );

        // ✅ Assign role
        $user->syncRoles([$role->name]);

        // 🔥 MAIN LOGIC (assign all permissions)
        $permissions = Permission::all();

        if ($permissions->count()) {
            $role->syncPermissions($permissions);
        }

        // ✅ Reset cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
