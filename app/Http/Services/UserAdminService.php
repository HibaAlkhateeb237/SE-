<?php

namespace App\Http\Services;

use App\Http\Repositories\UserAdminRepository;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserAdminService
{
    protected $repo;

    public function __construct(UserAdminRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listUsers()
    {
        return ApiResponse::success("Users fetched successfully", $this->repo->getAllUsers());
    }

    public function getUser($id)
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return ApiResponse::error("User not found", [], 404);
        }

        return ApiResponse::success("User fetched successfully", $user);
    }

    public function createUser($request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = $this->repo->create($data);

        return ApiResponse::success("User created successfully", $user);
    }

    public function updateUser($request, $id)
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return ApiResponse::error("User not found", [], 404);
        }

        $this->repo->update($user, $request->only(['name', 'email']));

        return ApiResponse::success("User updated successfully", $user);
    }

    public function deleteUser($id)
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return ApiResponse::error("User not found", [], 404);
        }

        $this->repo->delete($user);

        return ApiResponse::success("User deleted successfully");
    }

    public function assignRole($request, $id)
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return ApiResponse::error("User not found", [], 404);
        }

        if (!Role::where('name', $request->role)->exists()) {
            return ApiResponse::error("Role does not exist", [], 404);
        }

        if ($user->hasRole($request->role)) {
            return ApiResponse::error("User already has this role", [], 400);
        }

        $user->assignRole($request->role);

        return ApiResponse::success("Role assigned successfully");
    }


    public function removeRole($request, $id)
    {
        $user = $this->repo->findById($id);

        if (!$user) {
            return ApiResponse::error("User not found", [], 404);
        }

        if (empty($request->role)) {
            return ApiResponse::error("Role is required", [], 422);
        }

        if (!$user->hasRole($request->role)) {
            return ApiResponse::error("User does not have this role", [], 400);
        }

        $user->removeRole($request->role);

        return ApiResponse::success("Role removed successfully");
    }

}
