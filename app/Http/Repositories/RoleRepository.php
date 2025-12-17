<?php

namespace App\Http\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleRepository
{
    public function getAllRoles()
    {
        return Role::all();
    }

    public function getRoleById($id)
    {
        return Role::with('permissions')->find($id);
    }

    public function createRole($data)
    {
        $role = Role::create(['name' => $data['name']]);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role->load('permissions');
    }

    public function updateRole($role, $data)
    {
        if (!empty($data['name'])) {
            $role->name = $data['name'];
            $role->save();
        }
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role->load('permissions');
    }

    public function deleteRole($role)
    {
        $role->delete();
    }

    public function getAllPermissions()
    {
        return Permission::all();
    }
}
