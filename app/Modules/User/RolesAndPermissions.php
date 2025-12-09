<?php

namespace App\Modules\User;

use App\Modules\User\Enums\Roles;
use App\Modules\User\Enums\RoleType;

class RolesAndPermissions
{
    public function get(): array
    {
        return [
            new RoleFactory(
                RoleType::INTERNAL,
                Roles::SUPER_ADMIN,
                [
                    PermissionProperties::canViewDashboard(),
                    PermissionProperties::canListRoles(),
                    PermissionProperties::canCreateRoles(),
                    PermissionProperties::canEditRoles(),
                    PermissionProperties::canViewRoles(),
                    PermissionProperties::canListInvites(),
                    PermissionProperties::canCreateInvite(),
                    PermissionProperties::canViewCommissionSettings(),
                    PermissionProperties::canEditCommissionSettings(),
                    PermissionProperties::canListItems(),
                    PermissionProperties::canViewItems(),
                    PermissionProperties::canApproveItem(),
                    PermissionProperties::canRejectItem(),
                    PermissionProperties::canSetItemCommission(),
                    PermissionProperties::canListVendors(),
                    PermissionProperties::canViewVendor(),
                    PermissionProperties::canBlockVendor(),
                    PermissionProperties::canUnblockVendor(),
                    PermissionProperties::canTrackInvitedUserActivity(),
                ],
            ),
        ];
    }
}
