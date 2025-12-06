<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\PermissionProperty;
use App\Models\Role;
use App\Models\RoleProperty;
use App\Modules\User\Enums\Roles;
use App\Modules\User\Interfaces\RoleInterface;
use App\Modules\User\RolesAndPermissions;
use Illuminate\Console\Command;

class UpdateRolesAndPermissions extends Command
{
    protected $signature = 'app:update-roles-and-permissions';

    protected $description = 'It updates the roles and permissions';

    public function handle(): void
    {
        $roles = (new RolesAndPermissions)->get();

        foreach ($roles as $role) {
            $roleModel = Role::firstOrCreate(['name' => $role->name()->value]);

            $rolePermissions = [];
            foreach ($role->permissions() as $permission) {

                $existingPermission = Permission::where('name', $permission->permission()->value)->first();

                if ($existingPermission) {
                    if ($existingPermission->name !== $permission->permission()->value) {
                        $existingPermission->update(['name' => $permission->permission()->value]);
                    }
                    $permissionModel = $existingPermission;
                } else {
                    $permissionModel = Permission::create(['name' => $permission->permission()->value]);
                }

                PermissionProperty::updateOrCreate(
                    ['permission_id' => $permissionModel->id],
                    ['type' => $permission->type(), 'category' => $permission->category()]
                );

                $rolePermissions[] = $permissionModel->name;
            }

            $roleModel->syncPermissions($rolePermissions);

            RoleProperty::updateOrCreate(['role_id' => $roleModel->id], [
                'type' => $role->type(),
                'editable' => $roleModel->name !== Roles::SUPER_ADMIN->value,
            ]);
        }

        $superAdminRole = Role::where('name', Roles::SUPER_ADMIN->value)->first();
        if ($superAdminRole) {
            $allPermissions = Permission::pluck('name')->toArray();
            $superAdminRole->syncPermissions($allPermissions);
        }

        $this->info('Roles and permissions updated successfully.');
    }
}
