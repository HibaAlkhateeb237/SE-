<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class ReportsController extends Controller
{
    public function dailyTransactions()
    {
        $transactions = Transaction::whereDate('created_at', today())
            ->with('account')
            ->get();

        return response()->json([
            'date' => today()->toDateString(),
            'count' => $transactions->count(),
            'total_amount' => $transactions->sum('amount'),
            'transactions' => $transactions
        ]);
    }
    public function accountSummary()
    {
        $accounts = \App\Models\Account::with('user', 'accountType')->get();

        return response()->json(
            $accounts->map(fn ($a) => [
                'account_id' => $a->id,
                'user' => $a->user->name,
                'type' => $a->accountType?->name,
                'balance' => $a->balance,
                'state' => $a->state
            ])
        );
    }
    public function auditLogs()
    {
        return response()->json(
            \App\Models\AuditLog::latest()->paginate(20)
        );
    }


}
