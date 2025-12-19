<?php


namespace App\Strategies\Interest;

use App\Models\Account;


class LoanInterestStrategy implements InterestStrategyInterface
{
    public function calculate(Account $account): float
    {
        // 5% على المبلغ
        return $account->balance * 0.05;
    }
}
