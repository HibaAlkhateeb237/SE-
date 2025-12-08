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
        // تحميل الموديلات
        $parent = $this->accountRepository->findById($parentId);
        $child = $this->accountRepository->findById($childId);

        // 1) لا تسمح أن يكون نفس الحساب هو الأب والابن
        if ($parent->id === $child->id) {
            throw new \Exception('A account cannot be parent of itself', 400);
        }
//
//        // 2) صلاحيات: فقط مالك الحساب أو Admin مسموح
//        if ($parent->user_id !== $authUserId && !$this->userIsAdmin($authUserId)) {
//            throw new \Exception('Unauthorized', 403);
//        }
//
//        // 3) تحقق أن child لا يملك أب بالفعل (اختياري حسب سياسة النظام)
//        if ($child->parent_id) {
//            throw new \Exception('This account already has a parent', 400);
//        }

        // 4) منع الدورة: إذا كان الـ child هو ancestor للحساب parent،
        //    ربط child كابن للـ parent سيخلق حلقة.
//        if ($this->isAncestor($child->id, $parent)) {
//            throw new \Exception('Cannot attach: this operation would create a circular relationship', 400);
//        }

        // 5) قم بربط الابن بالأب واحفظ
        $child->parent_id = $parent->id;
        $child->save();

        // 6) استخدم composite لإرجاع التفاصيل كما في تصميمك
        $composite = new AccountComposite($parent);
        // reload children from DB for up-to-date data (اختياري)
        $parent->refresh();
        $composite = new AccountComposite($parent);

        return $composite->getDetails();
    }

    /**
     * هل candidateAncestorId هو سلف للحساب $node ؟
     * (نمشي للأعلى من $node.parent حتى null أو نلاقي الـ id)
     */
    protected function isAncestor(int $candidateAncestorId, \App\Models\Account $node): bool
    {
        $current = $node->parent; // أول أب

        while ($current) {
            if ($current->id === $candidateAncestorId) {
                return true;
            }
            $current = $current->parent; // استمر بالمشي للأعلى
        }

        return false;
    }

    /**
     * مثال بسيط للتحقق من دور Admin — عدّل حسب تطبيقك (Spatie مثلاً)
     */
    protected function userIsAdmin(int $userId): bool
    {
        $user = \App\Models\User::find($userId);
        if (!$user) return false;
        // إذا تستخدم Spatie:
        return $user->hasRole('Admin');
    }

}
