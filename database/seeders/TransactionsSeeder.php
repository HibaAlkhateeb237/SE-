<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Account;

class TransactionsSeeder extends Seeder
{
    public function run(): void
    {
        $account = Account::first();

        if ($account) {
            Transaction::create([
                'tx_id' => \Str::uuid(),
                'account_id' => $account->id,
                'type' => 'deposit',
                'amount' => 500,
                'currency' => 'USD',
                'status' => 'completed',
            ]);
        }
    }
}
