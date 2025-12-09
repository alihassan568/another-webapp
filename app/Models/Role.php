<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\Permission;

class Role extends SpatieRole
{
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }

    public function roleProperty(): HasOne
    {
        return $this->hasOne(RoleProperty::class);
    }
}
