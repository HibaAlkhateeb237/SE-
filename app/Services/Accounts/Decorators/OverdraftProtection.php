<?php

namespace App\Services\Accounts\Decorators;

class OverdraftProtection extends AccountDecorator
{
    public function getDescription(): string
    {
        return $this->account->getDescription() . ', Overdraft Protection';
    }

    public function getMonthlyFee(): float
    {
        return $this->account->getMonthlyFee() + 10;
    }
}

