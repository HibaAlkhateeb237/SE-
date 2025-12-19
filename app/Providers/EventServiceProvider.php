<?php

namespace App\Providers;

use App\Events\Account\LargeTransactionOccurred;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\Account\BalanceChanged;
use App\Listeners\Notifications\{SendEmailNotification,
    SendEmailNotificationForLargeTransaction,
    SendInAppNotificationForLargeTransaction,
    SendSmsNotification,
    SendInAppNotification,
    SendSmsNotificationForLargeTransaction};

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BalanceChanged::class => [
            SendEmailNotification::class,
            SendSmsNotification::class,
            SendInAppNotification::class,
        ],

        LargeTransactionOccurred::class => [
            SendEmailNotificationForLargeTransaction::class,
            SendSmsNotificationForLargeTransaction::class,
            SendInAppNotificationForLargeTransaction::class,

        ],
    ];



}
