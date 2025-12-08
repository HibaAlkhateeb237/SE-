<?php

namespace App\Http\Services;

use App\Http\Repositories\AccountRepository;
use App\Services\Accounts\AccountComposite;
use App\Services\Accounts\AccountLeaf;
use Illuminate\Support\Str;
use App\Models\Account;

class AccountService
{
    public function __construct(
        protected AccountRepository $accountRepository
    ) {}

    public function listTree()
    {
        return $this->accountRepository
            ->getRootAccounts()
            ->map(fn ($acc) => (new AccountComposite($acc))->getDetails());
    }

    public function create(array $data): Account
    {
        $data['uuid'] = Str::uuid();
        return $this->accountRepository->create($data);
    }

    public function show(int $id): array
    {
        $account = $this->accountRepository->findById($id);

        $component = $account->children->isNotEmpty()
            ? new AccountComposite($account)
            : new AccountLeaf($account);

        return $component->getDetails();
    }

    public function addChild(int $parentId, int $childId, int $authUserId): array
    {
        $parent = $this->accountRepository->findById($parentId);

        if ($parent->user_id !== $authUserId) {
            throw new \Exception('Unauthorized', 403);
        }

        $child = $this->accountRepository->findById($childId);

        if ($child->parent_id) {
            throw new \Exception('This account already has a parent', 400);
        }

        $composite = new AccountComposite($parent);
        $composite->add(new AccountLeaf($child));

        return $composite->getDetails();
    }
}
