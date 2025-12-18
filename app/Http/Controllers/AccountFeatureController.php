<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\Accounts\BasicAccount;
use App\Services\Accounts\Decorators\OverdraftProtection;
use App\Services\Accounts\Decorators\PremiumService;
use App\Services\Accounts\Decorators\Insurance;
use App\Http\Responses\ApiResponse;

class AccountFeatureController extends Controller
{
    public function show($id)
    {
        $accountModel = Account::find($id);

        if (!$accountModel) {
            return ApiResponse::error(
                'Account not found',
                ['account_id' => 'Invalid account id'],
                404
            );
        }

        // الحساب الأساسي
        $account = new BasicAccount($accountModel);

        // إضافة الميزات (Decorators) حسب القيم المخزنة
        if ($accountModel->has_overdraft) {
            $account = new OverdraftProtection($account);
        }

        if ($accountModel->is_premium) {
            $account = new PremiumService($account);
        }

        if ($accountModel->has_insurance) {
            $account = new Insurance($account);
        }

        // تجهيز البيانات
        $data = [
            'account_id'   => $accountModel->id,
            'description'  => $account->getDescription(),
            'balance'      => $account->getBalance(),
            'monthly_fee'  => $account->getMonthlyFee(),
            'features'     => [
                'overdraft' => (bool) $accountModel->has_overdraft,
                'premium'   => (bool) $accountModel->is_premium,
                'insurance' => (bool) $accountModel->has_insurance,
            ]
        ];

        return ApiResponse::success(
            'Account features loaded successfully',
            $data
        );
    }
}
