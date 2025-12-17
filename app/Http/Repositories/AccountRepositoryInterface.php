<?php

namespace App\Http\Repositories;

use App\Models\Account;

interface AccountRepositoryInterface
{
    public function findId(int $id): ?Account;
    public function save(Account $account): void;
}
