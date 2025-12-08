<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invite;
use App\Modules\User\Enums\Permissions;

class InvitePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_LIST_INVITES->value);
    }
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_CREATE_INVITE->value);
    }
}

