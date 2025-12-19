<?php

namespace App\Strategies\Interest;

use App\Models\Account;

class SavingsInterestStrategy implements InterestStrategyInterface
{
    public function calculate(Account $account): float
    {

        return $account->balance * 0.03;
    }
}
