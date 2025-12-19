<?php

namespace App\Events\Account;

use App\Models\Account;
use Illuminate\Foundation\Events\Dispatchable;

class BalanceChanged
{
    use Dispatchable;

    public function __construct(
        public Account $account,
        public float $oldBalance,
        public float $newBalance
    ) {}
}


