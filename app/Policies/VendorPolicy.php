<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Can list vendors');
    }

    public function view(User $user, User $vendor): bool
    {
        return $user->hasPermissionTo('Can view vendor');
    }

    public function block(User $user, User $vendor): bool
    {
        if ($user->id === $vendor->id) {
            return false;
        }

        return $user->hasPermissionTo('Can block vendor');
    }

    public function unblock(User $user, User $vendor): bool
    {
        return $user->hasPermissionTo('Can unblock vendor');
    }
}
