<?php

namespace App\Listeners\Notifications;

use App\Events\Account\BalanceChanged;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification
{
    public function handle(BalanceChanged $event)
    {
        $user = $event->account->user;

        if (!$user || !$user->email) {
            return;
        }

        $subject = "تحديث رصيد الحساب";
        $body = "مرحبًا {$user->name},\n\n"
            . "تم تغيير رصيد حسابك رقم {$event->account->id} من {$event->oldBalance} إلى {$event->newBalance}.\n\n"
            . "شكرًا لاستخدامك خدمتنا.";

        Mail::raw($body, function ($message) use ($user, $subject) {
            $message->to($user->email)
                ->subject($subject);
        });
    }
}


