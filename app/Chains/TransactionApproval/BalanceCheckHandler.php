<?php

namespace App\Chains\TransactionApproval;

use Exception;
use App\Models\Account;

class BalanceCheckHandler extends AbstractHandler
{
    public function handle(Account $account, float $amount): void
    {
        if ($account->balance < $amount) {
            throw new Exception('Insufficient balance');
        }

        parent::handle($account, $amount);
    }
}
