<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset old cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * ----------------------------------------------------
         * 1) Create Permissions
         * ----------------------------------------------------
         */
        $permissions = [
            // Customer
            'view accounts',
            'view transactions',
            'create support tickets',
            'view notifications',

            // Teller
            'deposit',
            'withdraw',
            'transfer',
            'view customers',

            // Manager
            'approve transactions',
            'freeze account',
            'unfreeze account',
            'view staff',
            'manage teller',
            'view audit logs',

            // Admin
            'manage roles',
            'manage permissions',
            'manage users',
            'system settings',
            'full access',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        /**
         * ----------------------------------------------------
         * 2) Create Roles
         * ----------------------------------------------------
         */
        $customer   = Role::firstOrCreate(['name' => 'Customer']);
        $teller     = Role::firstOrCreate(['name' => 'Teller']);
        $manager    = Role::firstOrCreate(['name' => 'Manager']);
        $admin      = Role::firstOrCreate(['name' => 'Admin']);

        /**
         * ----------------------------------------------------
         * 3) Assign Permissions to Roles
         * ----------------------------------------------------
         */

        // Customer role permissions
        $customer->givePermissionTo([
            'view accounts',
            'view transactions',
            'create support tickets',
            'view notifications',
        ]);

        // Teller role permissions
        $teller->givePermissionTo([
            'deposit',
            'withdraw',
            'transfer',
            'view customers',
            'view accounts',
            'view transactions',
        ]);

        // Manager role permissions
        $manager->givePermissionTo([
            'approve transactions',
            'freeze account',
            'unfreeze account',
            'view staff',
            'manage teller',
            'view audit logs',
        ]);

        // Admin role permissions
        $admin->givePermissionTo(Permission::all()); // Full access to everything
    }
}
