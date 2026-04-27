<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync {--cleanup}';
    protected $description = 'Sync permissions from config/modules.php';

    public function handle()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $config = config('modules.modules') ?? [];

        if (empty($config)) {
            $this->error('No modules found in config/modules.php');
            return;
        }

        $desired = collect();

        foreach ($config as $module => $actions) {
            foreach ($actions as $action) {

                $name = "$module.$action";

                Permission::updateOrCreate(
                    ['name' => $name, 'guard_name' => 'web'],
                    ['module' => $module]
                );

                $desired->push($name);
            }
        }

        // 🔥 Optional cleanup (remove stale permissions)
        if ($this->option('cleanup')) {
            Permission::whereNotIn('name', $desired->unique())->delete();
            $this->info('Stale permissions removed');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->info('Permissions synced successfully ✅');
    }
}
