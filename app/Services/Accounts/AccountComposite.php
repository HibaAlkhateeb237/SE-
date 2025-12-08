<?php
namespace App\Services\Accounts;

use App\Models\Account;

class AccountComposite implements AccountComponent
{
    protected Account $account;
    protected array $children = [];

    public function __construct(Account $account)
    {
        $this->account = $account;
        // جلب الأطفال من قاعدة البيانات عند البناء
        foreach ($account->children as $child) {
            $this->children[] = new AccountLeaf($child);
        }
    }

    public function getBalance(): float
    {
        $total = $this->account->balance;
        foreach ($this->children as $child) {
            $total += $child->getBalance();
        }
        return $total;
    }

    public function add(AccountComponent $account): void
    {
        $this->children[] = $account;
        // حفظ child في DB
        $childModel = $account instanceof AccountLeaf ? $account->getDetails()['id'] : null;
        if ($childModel) {
            $childAccount = \App\Models\Account::find($childModel);
            $childAccount->parent_id = $this->account->id;
            $childAccount->save();
        }
    }

    public function remove(AccountComponent $account): void
    {
        $this->children = array_filter($this->children, fn($c) => $c !== $account);
        // تحديث DB
        $childModel = $account instanceof AccountLeaf ? $account->getDetails()['id'] : null;
        if ($childModel) {
            $childAccount = \App\Models\Account::find($childModel);
            $childAccount->parent_id = null;
            $childAccount->save();
        }
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getDetails(): array
    {
        return [
            'id' => $this->account->id,
            'uuid' => $this->account->uuid,
            'balance' => $this->getBalance(),
            'account_type' => $this->account->type?->name,
            'currency'=>$this->account->currency,

            'children' => array_map(fn($c) => $c->getDetails(), $this->children),
        ];
    }
}
