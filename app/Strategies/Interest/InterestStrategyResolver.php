<?php

namespace App\Strategies\Interest;

use App\Models\Account;

class InterestStrategyResolver
{
    public static function resolve(Account $account): InterestStrategyInterface
    {
        return match ($account->type->code) {
            'SAVINGS'     => new SavingsInterestStrategy(),
            'CHECKING'    => new CheckingInterestStrategy(),
            'LOAN'        => new LoanInterestStrategy(),
            'INVESTMENT'  => new InvestmentInterestStrategy(
                marketRate: config('finance.market_rate', 0.07)
            ),
            default => throw new \Exception('Unsupported account type')
        };
    }
}
