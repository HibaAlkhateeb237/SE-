<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AccountTypesSeeder::class,
            UsersSeeder::class,
            AccountsSeeder::class,
            TransactionsSeeder::class,
            NotificationsSeeder::class,
            AuditLogsSeeder::class,
            SupportTicketsSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
