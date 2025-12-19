<?php

namespace App\Listeners\Notifications;

use App\Events\Account\BalanceChanged;
use App\Http\Services\NotificationService;

class SendInAppNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(BalanceChanged $event)
    {
        $title = 'Balance Updated';
        $body = "Your account #{$event->account->id} balance changed from {$event->oldBalance} to {$event->newBalance}";


        $this->notificationService->notify(
            userId: $event->account->user_id,
            channel: 'in_app',
            type: 'balance_change',
            title: $title,
            body: $body,
            meta: [
                'old_balance' => $event->oldBalance,
                'new_balance' => $event->newBalance,
                'account_id'  => $event->account->id,
            ]
        );
    }
}
