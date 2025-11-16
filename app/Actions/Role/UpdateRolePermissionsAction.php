<?php

namespace App\Actions\Role;

use App\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateRolePermissionsAction
{
    /**
     * Update role permissions.
     */
    public function execute(Role $role, array $permissionIds, bool $checked): void
    {
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        if ($checked) {
            $role->givePermissionTo($permissions);
        } else {
            foreach ($permissions as $permission) {
                $role->revokePermissionTo($permission);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

