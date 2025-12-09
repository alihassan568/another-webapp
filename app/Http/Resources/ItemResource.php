<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\DateHelper;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'sub_category' => $this->sub_category,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'discount_percentage' => $this->discount_percentage,
            'commission' => $this->commission,
            'requested_commission' => $this->requested_commission,
            'commission_status' => $this->commission_status,
            'status' => $this->status,
            'image' => $this->images[0] ?? null, // First image for backward compatibility
            'images' => $this->images, // Array of all images
            'rejection_reason' => $this->rejection_reason,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'business_name' => $this->user?->business_name,
            ],
            'created_at' => [
                'formatted' => DateHelper::toDate($this->created_at),
                'time' => DateHelper::toDateTime($this->created_at),
                'human' => DateHelper::toHumanDiff($this->created_at),
                'iso' => $this->created_at->toISOString(),
            ],
            'updated_at' => [
                'formatted' => DateHelper::toDate($this->updated_at),
                'time' => DateHelper::toDateTime($this->updated_at),
                'human' => DateHelper::toHumanDiff($this->updated_at),
                'iso' => $this->updated_at->toISOString(),
            ],
        ];
    }
}