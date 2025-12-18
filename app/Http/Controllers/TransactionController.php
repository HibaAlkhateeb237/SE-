<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Services\TransactionService;
use App\Http\Responses\ApiResponse;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $service) {}

    public function deposit(DepositRequest $request)
    {
        $transaction = $this->service->deposit(
            $request->account_id,
            $request->amount,
            auth()->id()
        );

        return ApiResponse::success('Deposit successful', $transaction);
    }

    public function withdraw(WithdrawRequest $request)
    {
        $transaction = $this->service->withdraw(
            $request->account_id,
            $request->amount,
            auth()->id()
        );

        return ApiResponse::success('Withdraw successful', $transaction);
    }

    public function transfer(TransferRequest $request)
    {
        $transaction = $this->service->transfer(
            $request->from_account_id,
            $request->to_account_id,
            $request->amount,
            auth()->id()
        );

        return ApiResponse::success('Transfer successful', $transaction);
    }

    public function index()
    {
        return ApiResponse::success(
            'Transaction history',
            $this->service->list()
        );
    }









}
