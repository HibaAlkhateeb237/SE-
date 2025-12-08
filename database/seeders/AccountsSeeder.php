<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\User;
use App\Models\AccountType;

class AccountsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $savingsType = AccountType::where('code', 'SAVINGS')->first();

        if ($user && $savingsType) {
            Account::firstOrCreate([
                'user_id' => $user->id,
                'account_type_id' => $savingsType->id,
            ], [
                'uuid' => \Str::uuid(),
                'balance' => 1500,
                'currency' => 'USD',
            ]);
        }
    }
}
