<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'channel' => 'in_app',
                'title' => 'Welcome!',
                'body' => 'Your account was created successfully.',
            ]);
        }
    }
}
