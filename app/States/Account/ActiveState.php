<?php

namespace App\States\Account;

use App\Models\Account;

class ActiveState implements AccountState
{
    public function deposit(Account $account, float $amount)
    {
        $account->balance += $amount;
        $account->save();
    }

    public function withdraw(Account $account, float $amount)
    {
        if ($amount > $account->balance) {
            throw new \Exception("Insufficient balance.");
        }

        $account->balance -= $amount;
        $account->save();
    }

    public function freeze(Account $account)
    {
        $account->changeState('frozen');
    }

    public function activate(Account $account)
    {
        // already active
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
        return 'active';
    }
}
