<?php

namespace App\Modules\User\Enums;

enum PermissionType: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';
}
