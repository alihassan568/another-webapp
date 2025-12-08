<?php

namespace App\Http\Resources;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'guard_name' => $this->guard_name ?? 'web',
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? DateHelper::toHumanDiff($this->updated_at) : null,
        ];

        if ($this->relationLoaded('roleProperty')) {
            $property = $this->roleProperty;
            $data['role_property'] = [
                'type' => $property?->type ?? '',
                'editable' => $property?->editable ?? true,
            ];
        }

        if ($this->relationLoaded('permissions')) {
            $data['permissions'] = PermissionResource::collection($this->permissions)->resolve();
        }

        return $data;
    }
}
