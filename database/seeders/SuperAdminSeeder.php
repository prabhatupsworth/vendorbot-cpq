<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $user = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('1234567890'),
            ]
        );

        $user->syncRoles(['super_admin']);

        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();
    }
}
