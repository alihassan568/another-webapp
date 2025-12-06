<?php

namespace App\Modules\User;

use App\Modules\User\Enums\PermissionCategory;
use App\Modules\User\Enums\Permissions;
use App\Modules\User\Enums\PermissionType;
use App\Modules\User\Interfaces\PermissionInterface;

class PermissionProperties
{
    public static function canViewDashboard(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::EXTERNAL,
            PermissionCategory::CAN_MANAGE_DASHBOARD,
            Permissions::CAN_VIEW_DASHBOARD,
        );
    }

    public static function canListRoles(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::EXTERNAL,
            PermissionCategory::CAN_MANAGE_ROLE,
            Permissions::CAN_LIST_ROLES,
        );
    }

    public static function canCreateRoles(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::INTERNAL,
            PermissionCategory::CAN_MANAGE_ROLE,
            Permissions::CAN_CREATE_ROLES,
        );
    }

    public static function canEditRoles(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::INTERNAL,
            PermissionCategory::CAN_MANAGE_ROLE,
            Permissions::CAN_EDIT_ROLE,
        );
    }

    public static function canViewRoles(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::EXTERNAL,
            PermissionCategory::CAN_MANAGE_ROLE,
            Permissions::CAN_VIEW_ROLE,
        );
    }
    public static function canListInvites(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::EXTERNAL,
            PermissionCategory::CAN_MANAGE_USER,
            Permissions::CAN_LIST_INVITES,
        );
    }
    public static function canCreateInvite(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::INTERNAL,
            PermissionCategory::CAN_MANAGE_USER,
            Permissions::CAN_CREATE_INVITE,
        );
    }
    public static function canViewCommissionSettings(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::EXTERNAL,
            PermissionCategory::CAN_MANAGE_COMMISSION,
            Permissions::CAN_VIEW_COMMISSION_SETTINGS,
        );
    }
    public static function canEditCommissionSettings(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::INTERNAL,
            PermissionCategory::CAN_MANAGE_COMMISSION,
            Permissions::CAN_EDIT_COMMISSION_SETTINGS,
        );
    }
    public static function canListItems(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::EXTERNAL,
            PermissionCategory::CAN_MANAGE_ITEMS,
            Permissions::CAN_LIST_ITEMS,
        );
    }
    public static function canApproveItem(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::INTERNAL,
            PermissionCategory::CAN_MANAGE_ITEMS,
            Permissions::CAN_APPROVE_ITEM,
        );
    }
    public static function canRejectItem(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::INTERNAL,
            PermissionCategory::CAN_MANAGE_ITEMS,
            Permissions::CAN_REJECT_ITEM,
        );
    }
    public static function canSetItemCommission(): PermissionInterface
    {
        return new PermissionFactory(
            PermissionType::INTERNAL,
            PermissionCategory::CAN_MANAGE_ITEMS,
            Permissions::CAN_SET_ITEM_COMMISSION,
        );
    }
}