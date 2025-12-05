<?php
namespace App\Services\Accounts;

interface AccountComponent
{
    public function getBalance(): float;
    public function add(AccountComponent $account): void;
    public function remove(AccountComponent $account): void;
    public function getChildren(): array;
    public function getDetails(): array;
}
