<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        // Helper to select localized fields dynamically
        $name = $locale === 'ar' ? $this->name_ar : $this->name_fr;

        return [
            'id' => $this->id,
            'name' => $name,
            'email' => $this->email,
            'isActive' => (bool) $this->is_active,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),

            'person' => new PersonResource($this->whenLoaded('person')),
            'avatar' => new PersonResource($this->whenLoaded('avatar')),

        ];
    }
}
