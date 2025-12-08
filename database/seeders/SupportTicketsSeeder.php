<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            SupportTicket::create([
                'user_id' => $user->id,
                'subject' => 'Test Ticket',
                'message' => 'This is a test support message.',
            ]);
        }
    }
}
