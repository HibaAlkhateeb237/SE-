<?php

namespace App\Services\Accounts\Decorators;

class Insurance extends AccountDecorator
{
    public function getDescription(): string
    {
        return $this->account->getDescription() . ', Insurance';
    }

    public function getMonthlyFee(): float
    {
        return $this->account->getMonthlyFee() + 15;
    }
}
