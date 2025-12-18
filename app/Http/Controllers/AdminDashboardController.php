<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'users' => [
                'total' => User::count(),
                'customers' => User::role('Customer')->count(),
                'staff' => User::role(['Teller', 'Manager', 'Admin'])->count(),
            ],

            'accounts' => [
                'total' => Account::count(),
                'active' => Account::where('state', 'active')->count(),
                'frozen' => Account::where('state', 'frozen')->count(),
                'suspended' => Account::where('state', 'suspended')->count(),
            ],

            'transactions' => [
                'total' => Transaction::count(),
                'today' => Transaction::whereDate('created_at', today())->count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'failed' => Transaction::where('status', 'failed')->count(),
            ],

            'support_tickets' => [
                'open' => SupportTicket::where('status', 'open')->count(),
                'closed' => SupportTicket::where('status', 'closed')->count(),
            ],

            'system' => [
                'database_status' => $this->checkDatabase(),
                'last_audit_logs' => AuditLog::latest()->limit(5)->get(),
            ]
        ]);
    }

    private function checkDatabase(): string
    {
        try {
            DB::connection()->getPdo();
            return 'connected';
        } catch (\Exception $e) {
            return 'down';
        }
    }
}

