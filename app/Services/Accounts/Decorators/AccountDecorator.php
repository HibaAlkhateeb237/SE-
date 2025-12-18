<?php

namespace App\Services\Accounts\Decorators;

use App\Services\Accounts\AccountInterface;

abstract class AccountDecorator implements AccountInterface
{
    protected AccountInterface $account;

    public function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }

    public function getBalance(): float
    {
        return $this->account->getBalance();
    }

    public function getDescription(): string
    {
        return $this->account->getDescription();
    }

    public function getMonthlyFee(): float
    {
        return $this->account->getMonthlyFee();
    }
}
