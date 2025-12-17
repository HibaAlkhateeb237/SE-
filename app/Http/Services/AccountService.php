<?php

namespace App\Http\Services;

use App\Http\Repositories\AccountRepository;
use App\Http\Repositories\AccountRepositoryInterface;
use App\Services\Accounts\AccountComposite;
use App\Services\Accounts\AccountLeaf;
use Illuminate\Support\Str;
use App\Models\Account;

class AccountService
{
    public function __construct(
        protected AccountRepository $accountRepository,
        protected AccountRepositoryInterface $accountRepositoryInterface,
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

//    public function addChild(int $parentId, int $childId, int $authUserId): array
//    {
//        $parent = $this->accountRepository->findById($parentId);
//
//        if ($parent->user_id !== $authUserId) {
//            throw new \Exception('Unauthorized', 403);
//        }
//
//        $child = $this->accountRepository->findById($childId);
//
//        if ($child->parent_id) {
//            throw new \Exception('This account already has a parent', 400);
//        }
//
//        $composite = new AccountComposite($parent);
//        $composite->add(new AccountLeaf($child));
//
//        return $composite->getDetails();
//    }

// داخل Service (مثلاً AccountService)

    public function addChild(int $parentId, int $childId, int $authUserId): array
    {

        $parent = $this->accountRepository->findById($parentId);
        $child = $this->accountRepository->findById($childId);


        if ($parent->id === $child->id) {
            throw new \Exception('A account cannot be parent of itself', 400);
        }



        $child->parent_id = $parent->id;
        $child->save();


        $composite = new AccountComposite($parent);

        $parent->refresh();
        $composite = new AccountComposite($parent);

        return $composite->getDetails();
    }


//    protected function isAncestor(int $candidateAncestorId, \App\Models\Account $node): bool
//    {
//        $current = $node->parent;
//
//        while ($current) {
//            if ($current->id === $candidateAncestorId) {
//                return true;
//            }
//            $current = $current->parent;
//        }
//
//        return false;
//    }
//
//
//    protected function userIsAdmin(int $userId): bool
//    {
//        $user = \App\Models\User::find($userId);
//        if (!$user) return false;
//        // إذا تستخدم Spatie:
//        return $user->hasRole('Admin');
//    }






    public function changeState(int $accountId, string $state): void
    {
        $account = $this->accountRepositoryInterface->findId($accountId);

        if (!$account) {
            throw new \Exception("Account not found");
        }

        switch ($state) {
            case 'active':
                $account->getStateInstance()->activate($account);
                break;
            case 'frozen':
                $account->getStateInstance()->freeze($account);
                break;
            case 'suspended':
                $account->getStateInstance()->suspend($account);
                break;
            case 'closed':
                $account->getStateInstance()->close($account);
                break;
        }

        $this->accountRepositoryInterface->save($account);
    }









}
