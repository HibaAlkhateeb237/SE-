<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;

class AuditLogsSeeder extends Seeder
{
    public function run(): void
    {
        AuditLog::create([
            'actor_type' => 'system',
            'action' => 'init',
            'description' => 'System initialized.',
        ]);
    }
}
