<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'displayName' => $this->display_name,
            'realName' => $this->real_name,
            'path' => $this->path,
            'url' => $this->url ?? asset("storage/{$this->path}"), // fallback to storage URL
            'size' => $this->size,
            'isActive' => $this->is_active,
            'useCase' => $this->use_case,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
