<?php

namespace App\Modules\User\Enums;

enum RoleType: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';
}
