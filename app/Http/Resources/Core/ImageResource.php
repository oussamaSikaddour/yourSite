<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,                        // Unique identifier for the image
            "name"          => $this->name,                      // Original name of the uploaded image
            "path"          => $this->path,                      // Storage path of the image on the server
            "url"           => $this->url,                       // Public URL to access the image
            "size"          => round($this->size / 1048576, 2),  // Image size in megabytes (MB), rounded to 2 decimals
            "width"         => $this->width,                     // Width of the image in pixels
            "height"        => $this->height,                    // Height of the image in pixels
            "status"        => $this->status,                    // Status of the image (e.g., active, pending, etc.)
            "useCase"       => $this->use_case,                  // Purpose of the image (e.g., profile_pic, banner, etc.)
            "imageableId"   => $this->imageable_id,              // ID of the related model (polymorphic)
            "imageableType" => $this->imageable_type,            // Class name of the related model (polymorphic)
        ];
    }
}
