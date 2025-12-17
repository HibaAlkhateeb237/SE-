<?php

namespace App\Http\Services;

use App\Http\Repositories\RoleRepository;
use Illuminate\Support\Facades\Validator;

class RoleService
{
    protected $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listRoles()
    {
        return $this->repository->getAllRoles();
    }

    public function getRole($id)
    {
        return $this->repository->getRoleById($id);
    }

    public function listPermissions()
    {
        return $this->repository->getAllPermissions();
    }

    public function createRole(array $data)
    {
        return $this->repository->createRole($data);
    }

    public function updateRole($role, array $data)
    {
        return $this->repository->updateRole($role, $data);
    }

    public function deleteRole($role)
    {
        $this->repository->deleteRole($role);
    }
}
