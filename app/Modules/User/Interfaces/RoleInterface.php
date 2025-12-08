<?php

namespace App\Modules\User\Interfaces;

use App\Modules\User\Enums\Roles;
use App\Modules\User\Enums\RoleType;

interface RoleInterface
{
    public function type(): RoleType;

    public function name(): Roles;

    /** @return PermissionInterface[] */
    public function permissions(): array;
}
