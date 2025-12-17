<?php

namespace App\States\Account;

use App\Models\Account;

class FrozenState implements AccountState
{
    public function deposit(Account $account, float $amount)
    {
        throw new \Exception("Account is frozen. Cannot deposit.");
    }

    public function withdraw(Account $account, float $amount)
    {
        throw new \Exception("Account is frozen. Cannot withdraw.");
    }

    public function freeze(Account $account)
    {
        // already frozen
    }

    public function activate(Account $account)
    {
        $account->changeState('active');
    }

    public function suspend(Account $account)
    {
        $account->changeState('suspended');
    }

    public function close(Account $account)
    {
        $account->changeState('closed');
    }

    public function getName(): string
    {
        return 'frozen';
    }
}
