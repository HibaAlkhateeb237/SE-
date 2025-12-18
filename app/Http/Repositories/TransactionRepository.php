<?php

namespace App\Http\Repositories;

use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function all()
    {
       // return Transaction::with(['account','relatedAccount','user'])->latest()->get();

        return Transaction::get();

    }


    public function findPending(int $id)
    {
        return Transaction::where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();
    }



    public function getByUser(int $userId)
    {
        return Transaction::whereIn('account_id', function ($query) use ($userId) {
            $query->select('id')
                ->from('accounts')
                ->where('user_id', $userId);
        })
            ->latest()
            ->get();
    }


}
