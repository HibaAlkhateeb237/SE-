<?php

namespace App\Chains\TransactionApproval;

use Exception;
use App\Models\Account;

class ManagerApprovalHandler extends AbstractHandler
{
    public function handle(Account $account, float $amount): void
    {
        if ($amount > 10000) {
            throw new Exception('Manager approval required');
        }

        parent::handle($account, $amount);
    }
}
