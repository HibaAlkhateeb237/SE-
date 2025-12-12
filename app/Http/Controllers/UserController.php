<?php

namespace App\Http\Controllers;

use App\Http\Services\UserAdminService;
use App\Http\Requests\UserAdminStoreRequest;
use App\Http\Requests\UserAdminUpdateRequest;
use App\Http\Requests\UserAdminAssignRoleRequest;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserAdminService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->listUsers();
    }

    public function show($id)
    {
        return $this->service->getUser($id);
    }

    public function store(UserAdminStoreRequest $request)
    {
        return $this->service->createUser($request);
    }

    public function update(UserAdminUpdateRequest $request, $id)
    {
        return $this->service->updateUser($request, $id);
    }

    public function destroy($id)
    {
        return $this->service->deleteUser($id);
    }

    public function assignRole(UserAdminAssignRoleRequest $request, $id)
    {
        return $this->service->assignRole($request, $id);
    }

    public function removeRole(UserAdminAssignRoleRequest $request, $id)
    {
        return $this->service->removeRole($request, $id);
    }
}
