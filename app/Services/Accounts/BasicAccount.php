<?php

namespace App\Services\Accounts;

use App\Models\Account;

class BasicAccount implements AccountInterface
{
    protected Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function getBalance(): float
    {
        return $this->account->balance;
    }

    public function getDescription(): string
    {
        return 'Basic Bank Account';
    }

    public function getMonthlyFee(): float
    {
        return 0;
    }
}
