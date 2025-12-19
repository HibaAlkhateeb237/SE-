<?php

namespace App\Listeners\Notifications;

use App\Events\Account\LargeTransactionOccurred;
use App\Http\Services\NotificationService;
use App\Models\Account;

class SendInAppNotificationForLargeTransaction
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(LargeTransactionOccurred $event)
    {
        $typeName = ucfirst($event->type); // Deposit, Withdrawal, Transfer
        $title = "Large Transaction Alert";
        $body = "A $typeName of {$event->amount} occurred for account #{$event->accountId}";

        if ($event->type === 'transfer' && $event->relatedAccountId) {
            $body .= " related to account #{$event->relatedAccountId}";
        }

        $account = Account::find($event->accountId);
        $userId = $account->user_id;


        $this->notificationService->notify(
            userId: $userId,
            channel: 'in_app',
            type: 'large_transaction',
            title: $title,
            body: $body,
            meta: [
                'amount' => $event->amount,
                'type' => $event->type,
                'related_account_id' => $event->relatedAccountId,
            ]
        );
    }
}
