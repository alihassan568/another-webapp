<?php

namespace App\Models;

use App\Modules\User\Enums\PermissionCategory;
use App\Modules\User\Enums\PermissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionProperty extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'category', 'permission_id'];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    protected function casts(): array
    {
        return [
            'type' => PermissionType::class,
            'category' => PermissionCategory::class,
        ];
    }
}
