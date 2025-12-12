<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {

        $admin = User::firstOrCreate(
            ['email' => 'admin@bank.com'],
            [
                'name' => 'Main Admin',
                'password' => bcrypt('admin123'), // غيرها لاحقاً
            ]
        );


        $role = Role::firstOrCreate(['name' => 'Admin','guard_name' => 'web']);


        $admin->assignRole(Role::where('name', 'Admin')->where('guard_name', 'web')->first());

    }
}
