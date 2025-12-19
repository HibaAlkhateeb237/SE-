<?php

namespace App\Listeners\Notifications;

use App\Events\Account\LargeTransactionOccurred;
use App\Models\Account;
use function App\Helpers\sendWhatsAppMessage;

class SendSmsNotificationForLargeTransaction
{
    public function handle(LargeTransactionOccurred $event)
    {
        $account = Account::with('user')->find($event->accountId);

        if (! $account || ! $account->user) {
            return;
        }

        $user = $account->user;
        $type = ucfirst($event->type);

        $message = "Hi {$user->name}, a {$type} of amount {$event->amount} occurred on your account.";

        if ($event->type === 'transfer' && $event->relatedAccountId) {
            $message .= " Related account ID: {$event->relatedAccountId}.";
        }

        sendWhatsAppMessage($user->phone, $message);
    }
}
