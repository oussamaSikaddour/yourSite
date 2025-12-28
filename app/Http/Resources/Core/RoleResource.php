<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id, // Unique identifier of the role
            "slug" => $this->slug, // Slug (string identifier) of the role

            // Include users only if the 'users' relationship is loaded
            "users" => UserResource::collection($this->whenLoaded("users")),

            // Include the pivot field 'created_at' from the role_user table (if the pivot is loaded)
            'attachedAt' => $this->whenPivotLoaded('role_user', function () {
                return $this->pivot->created_at?->toISOString(); // Convert to ISO 8601 string if not null
            }),
        ];
    }
}
