<?php

namespace App\Chains\TransactionApproval;

use Exception;
use App\Models\Account;

class AccountStatusHandler extends AbstractHandler
{
    public function handle(Account $account, float $amount): void
    {
        if ($account->state !== 'active') {
            throw new Exception('Account is not active');
        }

        parent::handle($account, $amount);
    }
}
