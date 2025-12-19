<?php


namespace App\Events\Account;

use Illuminate\Foundation\Events\Dispatchable;

class LargeTransactionOccurred
{

    use Dispatchable;

    public function __construct(
        public float $amount,
        public int $accountId,
        public string $type, // 'deposit', 'withdrawal', 'transfer'
        public ?int $relatedAccountId = null // فقط للتحويل
    ) {}
}
