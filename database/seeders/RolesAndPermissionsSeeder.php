<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = [
            'view accounts',
            'view transactions',
            'create support tickets',
            'view notifications',

            'deposit',
            'withdraw',
            'transfer',
            'view customers',

            'approve transactions',
            'freeze account',
            'unfreeze account',
            'view audit logs',

            'manage users',
            'manage roles',
            'manage permissions',
            'system dashboard',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'sanctum',
            ]);
        }


        $customer = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'sanctum']);
        $teller   = Role::firstOrCreate(['name' => 'Teller', 'guard_name' => 'sanctum']);
        $manager  = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'sanctum']);
        $admin    = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'sanctum']);


        $customer->syncPermissions([
            'view accounts',
            'view transactions',
            'create support tickets',
            'view notifications',
        ]);

        $teller->syncPermissions([
            'deposit',
            'withdraw',
            'transfer',
            'view customers',
            'view accounts',
        ]);

        $manager->syncPermissions([
            'approve transactions',
            'freeze account',
            'unfreeze account',
            'view audit logs',
        ]);

        $admin->syncPermissions(Permission::where('guard_name', 'sanctum')->get());
    }
}
