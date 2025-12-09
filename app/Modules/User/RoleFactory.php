<?php

namespace App\Modules\User;

use App\Modules\User\Enums\Roles;
use App\Modules\User\Enums\RoleType;
use App\Modules\User\Interfaces\PermissionInterface;
use App\Modules\User\Interfaces\RoleInterface;

readonly class RoleFactory implements RoleInterface
{
    /**
     * @param  PermissionInterface[]  $permissions
     */
    public function __construct(
        private RoleType $type,
        private Roles $name,
        private array $permissions
    ) {}

    public function type(): RoleType
    {
        return $this->type;
    }

    public function name(): Roles
    {
        return $this->name;
    }

    /** @return PermissionInterface[] */
    public function permissions(): array
    {
        return $this->permissions;
    }
}
