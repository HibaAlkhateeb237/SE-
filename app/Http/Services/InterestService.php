<?php

namespace App\Http\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Strategies\Interest\InterestStrategyResolver;
use App\Http\Repositories\TransactionRepositoryInterface;

class InterestService
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepo
    ) {}

    public function applyInterest(Account $account)
    {
        $strategy = InterestStrategyResolver::resolve($account);
        $interest = $strategy->calculate($account);

        if ($interest == 0) {
            return null;
        }

        DB::transaction(function () use ($account, $interest) {
            $account->deposit($interest);
        });

        return $this->transactionRepo->create([
            'tx_id'      => Str::uuid(),
            'account_id'=> $account->id,
            'type'      => 'interest',
            'amount'    => $interest,
            'status'    => 'completed',
            'meta'      => [
                'strategy' => class_basename($strategy)
            ]
        ]);
    }
}
