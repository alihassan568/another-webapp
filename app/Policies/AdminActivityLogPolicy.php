<?php

namespace App\Policies;

use App\Models\AdminActivityLog;
use App\Models\User;
use App\Modules\User\Enums\Permissions;
use Illuminate\Auth\Access\Response;

class AdminActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_TRACK_INVITED_USER_ACTIVIY);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdminActivityLog $adminActivityLog): bool
    {
        return $user->hasPermissionTo(Permissions::CAN_TRACK_INVITED_USER_ACTIVIY);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false; // Activity logs are created automatically, not manually
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdminActivityLog $adminActivityLog): bool
    {
        return false; // Activity logs should not be editable
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdminActivityLog $adminActivityLog): bool
    {
        return false; // Activity logs should not be deletable
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdminActivityLog $adminActivityLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdminActivityLog $adminActivityLog): bool
    {
        return false;
    }
}
