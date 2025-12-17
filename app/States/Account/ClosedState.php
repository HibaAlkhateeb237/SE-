<?php

namespace App\States\Account;

use App\Models\Account;

class ClosedState implements AccountState
{
    public function deposit(Account $account, float $amount)
    {
        throw new \Exception("Account is closed.");
    }

    public function withdraw(Account $account, float $amount)
    {
        throw new \Exception("Account is closed.");
    }

   /* public function freeze(Account $account) { }
    public function activate(Account $account) { }
    public function suspend(Account $account) { }
    public function close(Account $account) { }*/




    public function freeze(Account $account)
    {
        throw new \Exception("لا يمكن تجميد حساب مغلق");
    }

    public function activate(Account $account)
    {
        throw new \Exception("لا يمكن تفعيل حساب مغلق");
    }

    public function suspend(Account $account)
    {
        throw new \Exception("لا يمكن تعليق حساب مغلق");
    }

    public function close(Account $account)
    {
        throw new \Exception("الحساب مغلق بالفعل");
    }



    public function getName(): string
    {
        return 'closed';
    }
}
