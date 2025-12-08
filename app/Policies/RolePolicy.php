<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Modules\User\Enums\Permissions;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_LIST_ROLES);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_CREATE_ROLES);
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_VIEW_ROLE);
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_EDIT_ROLE);
    }
}
