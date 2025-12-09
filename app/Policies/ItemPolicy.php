<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invite;
use App\Modules\User\Enums\Permissions;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_LIST_ITEMS->value);
    }
    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_VIEW_ITEMS->value);
    }
    public function approve(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_APPROVE_ITEM->value);
    }
    public function reject(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_REJECT_ITEM->value);
    }
    public function setCommission(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_SET_ITEM_COMMISSION->value);
    }
}

