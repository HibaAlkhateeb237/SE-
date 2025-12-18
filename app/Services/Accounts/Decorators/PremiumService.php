<?php

namespace App\Services\Accounts\Decorators;

class PremiumService extends AccountDecorator
{
    public function getDescription(): string
    {
        return $this->account->getDescription() . ', Premium Service';
    }

    public function getMonthlyFee(): float
    {
        return $this->account->getMonthlyFee() + 20;
    }
}
