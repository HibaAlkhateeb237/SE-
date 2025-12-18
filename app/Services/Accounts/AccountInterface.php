<?php

namespace App\Services\Accounts;

interface AccountInterface
{
    public function getBalance(): float;
    public function getDescription(): string;
    public function getMonthlyFee(): float;
}
