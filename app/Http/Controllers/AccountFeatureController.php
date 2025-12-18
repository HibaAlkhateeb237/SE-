<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\Accounts\BasicAccount;
use App\Services\Accounts\Decorators\OverdraftProtection;
use App\Services\Accounts\Decorators\PremiumService;
use App\Services\Accounts\Decorators\Insurance;

class AccountFeatureController extends Controller
{
    public function show($id)
    {
        $accountModel = Account::findOrFail($id);

        // الحساب الأساسي
        $account = new BasicAccount($accountModel);

        // إضافة ميزات حسب الحاجة
        if ($accountModel->has_overdraft) {
            $account = new OverdraftProtection($account);
        }

        if ($accountModel->is_premium) {
            $account = new PremiumService($account);
        }

        if ($accountModel->has_insurance) {
            $account = new Insurance($account);
        }

        return response()->json([
            'description' => $account->getDescription(),
            'balance' => $account->getBalance(),
            'monthly_fee' => $account->getMonthlyFee(),
        ]);
    }
}
