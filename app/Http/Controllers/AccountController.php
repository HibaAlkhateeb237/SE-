<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeAccountStateRequest;
use App\Http\Services\AccountService;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\AddChildRequest;
use App\Http\Responses\ApiResponse;
use App\Models\AccountType;
use Exception;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService $accountService
    ) {}

    public function index()
    {
        $data = $this->accountService->listTree();

        return ApiResponse::success(
            'Accounts fetched successfully',
            $data
        );
    }

    public function store(AccountStoreRequest $request)
    {
        $account = $this->accountService->create($request->validated());

        return ApiResponse::success(
            'Account created successfully',
            $account,
            201
        );
    }

    public function show($id)
    {
        $data = $this->accountService->show($id);

        return ApiResponse::success(
            'Account details',
            $data
        );
    }

    public function addChild(AddChildRequest $request, $id)
    {
        try {
            $data = $this->accountService->addChild(
                $id,
                $request->child_id,
                auth()->id()
            );

            return ApiResponse::success(
                'Child account added successfully',
                $data
            );

        } catch (\Exception $e) {

            return ApiResponse::error(
                $e->getMessage(),
                [],
                $e->getCode() ?: 400
            );
        }
    }
    public function indexType()
    {
        $types = AccountType::query()
            ->select('id', 'code', 'name', 'rules')
            ->get();

        return ApiResponse::success(
            'Account types fetched successfully',
            $types
        );
    }


    //--------------------------------------------


    public function changeState(ChangeAccountStateRequest $request)
    {
        try {
            $this->accountService->changeState(
                $request->accountId(),
                $request->state
            );

            return ApiResponse::success("Account state updated successfully");
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), [], 400);
        }
    }

    //--------------------------------------------------------------


    public function update(AccountUpdateRequest $request, $id)
    {
        $account = $this->accountService->update($id, $request->validated());

        if (!$account) {
            return ApiResponse::error(
                'Account not found',
                [],
                404
            );
        }

        return ApiResponse::success(
            'Account updated successfully',
            $account
        );
    }










}
