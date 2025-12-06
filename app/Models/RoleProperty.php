<?php

namespace App\Models;

use App\Modules\User\Enums\RoleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleProperty extends Model
{
    protected $fillable = ['type', 'role_id', 'editable'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    protected function casts(): array
    {
        return [
            'type' => RoleType::class,
            'editable' => 'boolean',
        ];
    }
}
