<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InviteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'role' => $this->role ? ['name' => $this->role->name] : null,
            'status' => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}

