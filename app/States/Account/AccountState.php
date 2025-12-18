<?php

namespace App\States\Account;

use App\Models\Account;

interface AccountState
{
    public function deposit(Account $account, float $amount);
    public function withdraw(Account $account, float $amount);

    public function freeze(Account $account);
    public function activate(Account $account);
    public function suspend(Account $account);
    public function close(Account $account);

    public function getName(): string;
}
