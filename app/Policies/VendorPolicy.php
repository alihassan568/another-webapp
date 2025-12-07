<?php

namespace App\Policies;

use App\Models\User;
use App\Modules\User\Enums\Permissions;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_LIST_VENDORS);
    }

    public function view(User $user, User $vendor): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_VIEW_VENDOR);
    }

    public function block(User $user, User $vendor): bool
    {
        if ($user->id === $vendor->id) {
            return false;
        }

        return $user->hasPermissionTo(Permissions::CAN_BLOCK_VENDOR);
    }

    public function unblock(User $user, User $vendor): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_UNBLOCK_VENDOR);
    }
}
