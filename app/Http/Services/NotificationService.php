<?php

namespace App\Http\Services;

use App\Models\Notification;

class NotificationService
{

    public function __construct()
    {

    }

    public function notify(
        int $userId,
        string $channel,   // email, sms, in_app
        string $type,      // balance_change, large_transaction
        string $title,
        string $body,
        array $meta = []
    ): void {
        Notification::create([
            'user_id' => $userId,
            'channel' => $channel,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body,
            'meta'    => $meta,
            // read سيتم ضبطه تلقائياً false
        ]);
    }


}



