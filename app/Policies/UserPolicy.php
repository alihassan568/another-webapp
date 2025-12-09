<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\User\Enums\Permissions;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_LIST_USERS);
    }
}
