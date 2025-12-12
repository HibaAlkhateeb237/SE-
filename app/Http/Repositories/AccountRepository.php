<?php

namespace App\Http\Repositories;

use App\Models\Account;

class AccountRepository
{
    public function getRootAccounts()
    {
        return Account::with([
            'type',
            'children' => function($q) {
                $q->where('user_id', auth()->id());
            },
            'children.type'
        ])
            ->whereNull('parent_id')
            ->where('user_id', auth()->id())
            ->get();
    }



    public function findById(int $id): Account
    {
        return Account::with(['type', 'children.type'])
            ->findOrFail($id);
    }

    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function attachChild(Account $parent, Account $child): void
    {
        $child->parent_id = $parent->id;
        $child->save();
    }
}
