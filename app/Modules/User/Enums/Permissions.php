<?php

namespace App\Modules\User\Enums;

enum Permissions: string
{
    case CAN_VIEW_DASHBOARD = 'can view dashboard';
    case CAN_LIST_ROLES = 'Can list roles';
    case CAN_CREATE_ROLES = 'Can create roles';
    case CAN_EDIT_ROLE = 'Can edit role';
    case CAN_VIEW_ROLE = 'Can view role';

    case CAN_LIST_INVITES = 'Can list invites';
    case CAN_CREATE_INVITE = 'Can create invite';

    case CAN_VIEW_COMMISSION_SETTINGS = 'Can view commission settings';
    case CAN_EDIT_COMMISSION_SETTINGS = 'Can edit commission settings';

    case CAN_LIST_ITEMS = 'Can list items';
    case CAN_VIEW_ITEMS = 'Can view items';
    case CAN_APPROVE_ITEM = 'Can approve item';
    case CAN_REJECT_ITEM = 'Can reject item';
    
    case CAN_SET_ITEM_COMMISSION = 'Can set item commission';

    case CAN_LIST_VENDORS = 'Can list vendors';
    case CAN_VIEW_VENDOR = 'Can view vendor';
    case CAN_BLOCK_VENDOR = 'Can block vendor';
    case CAN_UNBLOCK_VENDOR = 'Can unblock vendor';

    case CAN_TRACK_INVITED_USER_ACTIVIY = 'can track invited user activity';
}
