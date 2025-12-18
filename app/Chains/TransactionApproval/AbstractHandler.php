<?php

namespace App\Chains\TransactionApproval;

use App\Models\Account;

abstract class AbstractHandler implements TransactionApprovalHandler
{
    protected ?TransactionApprovalHandler $nextHandler = null;

    public function setNext(TransactionApprovalHandler $handler): TransactionApprovalHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(Account $account, float $amount): void
    {
        if ($this->nextHandler) {
            $this->nextHandler->handle($account, $amount);
        }
    }
}
