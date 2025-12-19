<?php


namespace App\Listeners\Notifications;

use App\Events\Account\LargeTransactionOccurred;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendEmailNotificationForLargeTransaction
{


    public function handle(LargeTransactionOccurred $event)
    {
        $account = \App\Models\Account::find($event->accountId);
        if (!$account) return;

        $user = $account->user;
        if (!$user || !$user->email) return;

        $type = ucfirst($event->type); // Deposit, Withdrawal, Transfer

        $body = "مرحبًا {$user->name},\n\n"
            . "تم تنفيذ عملية $type بمبلغ {$event->amount} على حسابك رقم {$account->id}.";

        if ($event->type === 'transfer' && $event->relatedAccountId) {
            $body .= " الحساب المرتبط: {$event->relatedAccountId}";
        }

        $subject = "عملية مالية كبيرة على حسابك";

        Mail::raw($body, function ($message) use ($user, $subject) {
            $message->to($user->email)
                ->subject($subject);
        });
    }







}

