<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OccupationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'experience'      => $this->experience,
            'isActive'        => $this->is_active,
            'user'            => new UserResource($this->whenLoaded('user')),
            'field'           => new FieldResource($this->whenLoaded('field')),
            'fieldSpecialty'  => new FieldSpecialtyResource($this->whenLoaded('fieldSpecialty')),
            'fieldGrade'      => new FieldGradeResource($this->whenLoaded('fieldGrade')),
        ];
    }
}
