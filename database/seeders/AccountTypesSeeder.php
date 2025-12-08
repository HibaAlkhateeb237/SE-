<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => 'SAVINGS', 'name' => 'Savings Account'],
            ['code' => 'CHECKING', 'name' => 'Checking Account'],
            ['code' => 'LOAN', 'name' => 'Loan Account'],
            ['code' => 'INVESTMENT', 'name' => 'Investment Account'],
        ];

        foreach ($types as $t) {
            DB::table('account_types')->updateOrInsert(
                ['code' => $t['code']],
                $t
            );
        }
    }
}
