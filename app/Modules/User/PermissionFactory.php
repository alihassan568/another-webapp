<?php

namespace App\Modules\User;

use App\Modules\User\Enums\PermissionCategory;
use App\Modules\User\Enums\Permissions;
use App\Modules\User\Enums\PermissionType;
use App\Modules\User\Interfaces\PermissionInterface;

readonly class PermissionFactory implements PermissionInterface
{
    public function __construct(
        private PermissionType $type,
        private PermissionCategory $category,
        private Permissions $permission
    ) {}

    public function type(): PermissionType
    {
        return $this->type;
    }

    public function category(): PermissionCategory
    {
        return $this->category;
    }

    public function permission(): Permissions
    {
        return $this->permission;
    }
}
