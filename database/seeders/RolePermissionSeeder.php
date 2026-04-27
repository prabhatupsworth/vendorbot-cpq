<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ✅ Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Get modules from config
        $modules = config('modules.modules') ?? [];

        if (empty($modules)) {
            $this->command->error('No modules found in config/modules.php');
            return;
        }

        // ✅ Create permissions dynamically
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {

                Permission::updateOrCreate(
                    [
                        'name' => "$module.$action",
                        'guard_name' => 'web'
                    ],
                    [
                        'module' => $module
                    ]
                );
            }
        }

        // ✅ Create ONLY super_admin role
      $role =  Role::updateOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);

        // ❌ No permission assignment here
        // handled in SuperAdminSeeder OR Gate::before

                // 🔥 AUTO ASSIGN ALL PERMISSIONS
        // ================================
        $allPermissions = Permission::pluck('name')->toArray();

        $role->syncPermissions($allPermissions);

        // ✅ Reset cache again
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Permissions & Super Admin role created ✅');
    }
}
