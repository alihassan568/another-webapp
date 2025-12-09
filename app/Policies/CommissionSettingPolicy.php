<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invite;
use App\Modules\User\Enums\Permissions;

class CommissionSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_VIEW_COMMISSION_SETTINGS->value);
    }
    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_EDIT_COMMISSION_SETTINGS->value);
    }
}

