<?php

namespace App\Http\Resources;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'guard_name' => $this->guard_name ?? 'web',
            'created_at' => $this->created_at ? DateHelper::toHumanDiff($this->created_at) : null,
            'updated_at' => $this->updated_at ? DateHelper::toHumanDiff($this->updated_at) : null,
        ];

        if ($this->relationLoaded('permissionProperty')) {
            $property = $this->permissionProperty;
            if ($property) {
                $data['permission_property'] = [
                    'type' => $property->type ?? '',
                    'editable' => $property->editable ?? true,
                    'description' => $property->description ?? '',
                ];
            }
        }

        return $data;
    }
}