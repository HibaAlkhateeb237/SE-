<?php

namespace App\Listeners\Notifications;

use App\Events\Account\BalanceChanged;
use function App\Helpers\sendWhatsAppMessage;

class SendSmsNotification
{
    public function handle(BalanceChanged $event)
    {
        $user = $event->account->user;
        $message = "Hi {$user->name}, your account balance changed from {$event->oldBalance} to {$event->newBalance}.";

        sendWhatsAppMessage($user->phone, $message);
    }
}

