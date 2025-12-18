<?php

namespace App\States\Account;

use App\Models\Account;

class SuspendedState implements AccountState
{
    public function deposit(Account $account, float $amount)
    {
        throw new \Exception("Account is suspended.");
    }

    public function withdraw(Account $account, float $amount)
    {
        throw new \Exception("Account is suspended.");
    }

    public function freeze(Account $account)
    {
        throw new \Exception("Suspended account cannot be frozen.");
    }

    public function activate(Account $account)
    {
        $account->changeState('active');
    }

    public function suspend(Account $account)
    {
        // already suspended
    }

    public function close(Account $account)
    {
        $account->changeState('closed');
    }

    public function getName(): string
    {
        return 'suspended';
    }
}
