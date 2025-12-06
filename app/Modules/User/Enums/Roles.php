<?php

namespace App\Modules\User\Enums;

enum Roles: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case MANAGER = 'Manager';
    case EDITOR = 'Editor';
}
