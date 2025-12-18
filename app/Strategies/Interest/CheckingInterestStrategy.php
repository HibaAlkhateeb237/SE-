<?php

namespace App\Strategies\Interest;

use App\Models\Account;


class CheckingInterestStrategy implements InterestStrategyInterface
{
    public function calculate(Account $account): float
    {
        return 0;
    }
}
