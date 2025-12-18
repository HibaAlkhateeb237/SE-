<?php

namespace App\Strategies\Interest;

use App\Models\Account;

interface InterestStrategyInterface
{
    public function calculate(Account $account): float;
}
