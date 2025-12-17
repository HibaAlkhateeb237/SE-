<?php

namespace App\Chains\TransactionApproval;

use Exception;
use App\Models\Account;

class AccountOwnershipHandler extends AbstractHandler
{
    public function handle(Account $account, float $amount): void
    {
        if ($account->user_id !== auth()->id()) {
            throw new Exception('You are not allowed to access this account (: ');
        }

        parent::handle($account, $amount);
    }
}
