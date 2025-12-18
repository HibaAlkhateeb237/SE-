<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SupportReportsController extends Controller
{
    /**
     * Daily transactions report
     */
    public function dailyTransactions(Request $request)
    {
        $date = $request->date ?? today();

        $transactions = Transaction::whereDate('created_at', $date)->get();

        return response()->json([
            'date' => $date,
            'total_transactions' => $transactions->count(),
            'total_amount' => $transactions->sum('amount'),
            'transactions' => $transactions,
        ]);
    }

    /**
     * Account summary report
     */
    public function accountSummary()
    {
        $accounts = Account::with(['user','type'])->get();

        return response()->json([
            'total_accounts' => $accounts->count(),
            'accounts' => $accounts->map(function ($account) {
                return [
                    'account_id' => $account->id,
                    'user' => $account->user->name ?? null,
                    'type' => $account->type->name ?? null,
                    'balance' => $account->balance,
                    'currency' => $account->currency,
                    'state' => $account->state,
                ];
            }),
        ]);
    }

    /**
     * Audit logs report
     */
    public function auditLogs(Request $request)
    {
        $logs = AuditLog::latest()->paginate(20);

        return response()->json([
            'total_logs' => $logs->total(),
            'logs' => $logs->items(),
        ]);
    }
}
