<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\AccountService;
use App\Http\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionApprovalController extends Controller
{


    public function __construct(
        protected TransactionService $transactionService
    ) {}


    public function approve($id, TransactionService $service)
    {
        $tx = $service->approve($id);

        return ApiResponse::success('Transaction approved', $tx);
    }

    public function reject(RejectRequest $request )
    {
        $tx = $this->transactionService->reject(
            $request->accountId(),
            $request->reason
        );

        return ApiResponse::success('Transaction rejected', $tx);
    }



    public function myTransactions()
    {
        $transactions = $this->transactionService->getMyTransactions();

        return ApiResponse::success(
            'My transactions retrieved successfully',
            $transactions
        );
    }



}
