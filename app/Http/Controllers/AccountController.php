<?php

namespace App\Http\Controllers;

use App\Http\Services\AccountService;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\AddChildRequest;
use App\Http\Responses\ApiResponse;
use App\Models\AccountType;

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


}
