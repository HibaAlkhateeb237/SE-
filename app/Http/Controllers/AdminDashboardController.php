<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\SupportTicket;

class AdminDashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'users' => [
                'total' => User::count(),
                'customers' => User::role('Customer')->count(),
                'staff' => User::role(['Teller', 'Manager'])->count(),
            ],
            'accounts' => [
                'total' => Account::count(),
                'active' => Account::where('state', 'active')->count(),
                'frozen' => Account::where('state', 'frozen')->count(),
            ],
            'transactions' => [
                'today' => Transaction::whereDate('created_at', today())->count(),
                'pending' => Transaction::where('status', 'pending')->count(),
            ],
            'support_tickets' => [
                'open' => SupportTicket::where('status', 'open')->count(),
            ]
        ]);
    }
}
