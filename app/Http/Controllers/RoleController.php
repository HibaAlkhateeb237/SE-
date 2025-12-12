<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\RoleService;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return ApiResponse::success('Roles fetched successfully', $this->service->listRoles());
    }

    public function show($id)
    {
        $role = $this->service->getRole($id);
        if (!$role) return ApiResponse::error('Role not found', null, 404);
        return ApiResponse::success('Role fetched successfully', $role);
    }

    public function indexPermission()
    {
        return ApiResponse::success('Permissions fetched', $this->service->listPermissions());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = $this->service->createRole($request->only(['name','permissions']));
        return ApiResponse::success('Role created successfully', $role);
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) return ApiResponse::error('Role not found', null, 404);
        if ($role->name === 'Admin') return ApiResponse::error('Admin role cannot be modified.', null, 403);

        $request->validate([
            'name' => 'sometimes|string|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role = $this->service->updateRole($role, $request->only(['name','permissions']));
        return ApiResponse::success('Role updated successfully', $role);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
       // dd($role->users());
        if (!$role) return ApiResponse::error('Role not found', null, 404);
        if ($role->name === 'Admin') return ApiResponse::error('Admin role cannot be deleted', null, 403);
        if ($role->users()->count() > 0)
            return ApiResponse::error('Role is assigned to users', null, 403);

        $this->service->deleteRole($role);
        return ApiResponse::success('Role deleted successfully');
    }
}
