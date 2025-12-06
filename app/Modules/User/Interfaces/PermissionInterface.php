<?php

namespace App\Modules\User\Interfaces;

use App\Modules\User\Enums\PermissionCategory;
use App\Modules\User\Enums\Permissions;
use App\Modules\User\Enums\PermissionType;

interface PermissionInterface
{
    public function type(): PermissionType;

    public function category(): PermissionCategory;

    public function permission(): Permissions;
}
