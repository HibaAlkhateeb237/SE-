<?php

namespace App\Chains\TransactionApproval;

use App\Models\Account;

interface TransactionApprovalHandler
{
    public function setNext(TransactionApprovalHandler $handler): TransactionApprovalHandler;

    public function handle(Account $account, float $amount): void;
}
