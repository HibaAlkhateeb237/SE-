<?php

namespace App\Http\Repositories;

interface TransactionRepositoryInterface
{
    public function create(array $data);
    public function all();
    public function findPending(int $id);
}
