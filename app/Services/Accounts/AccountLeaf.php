<?php
namespace App\Services\Accounts;

use App\Models\Account;

class AccountLeaf implements AccountComponent
{
    protected Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function getBalance(): float
    {
        return $this->account->balance;
    }

    public function add(AccountComponent $account): void
    {
        throw new \Exception("Cannot add child to leaf account");
    }

    public function remove(AccountComponent $account): void
    {
        throw new \Exception("Cannot remove child from leaf account");
    }

    public function getChildren(): array
    {
        return [];
    }

    public function getDetails(): array
    {
        return [
            'id' => $this->account->id,
            'uuid' => $this->account->uuid,
            'balance' => $this->account->balance,
            'state' => $this->account->state,
            'account_type' => $this->account->type?->name,
            'currency'=>$this->account->currency,
        ];
    }
}
