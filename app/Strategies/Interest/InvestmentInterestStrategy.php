<?php


namespace App\Strategies\Interest;

use App\Models\Account;



class InvestmentInterestStrategy implements InterestStrategyInterface
{
    public function __construct(
        protected float $marketRate
    ) {}

    public function calculate(Account $account): float
    {
        return $account->balance * $this->marketRate;
    }
}
