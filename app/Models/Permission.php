<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public function permissionProperty(): HasOne
    {
        return $this->hasOne(PermissionProperty::class);
    }
}
