<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
    public function test_account_creation()
    {
        $user = User::factory()->create();
        $account = Account::create([
            'uuid'=>\Illuminate\Support\Str::uuid(),
            'user_id'=>$user->id,
            'account_type_id'=>1,
            'balance'=>0,
        ]);
        $this->assertDatabaseHas('accounts',['id'=>$account->id]);
    }

}
