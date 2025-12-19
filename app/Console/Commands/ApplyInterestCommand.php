<?php
namespace App\Console\Commands;
use App\Http\Services\InterestService;
use App\Models\Account;
use Illuminate\Console\Command;


class ApplyInterestCommand extends Command

{
    protected $signature = 'interest:apply';

    public function handle(
        InterestService $service
    ) {
        Account::where('state', 'active')->each(function ($account) use ($service) {
            $service->applyInterest($account);
        });
    }
}
