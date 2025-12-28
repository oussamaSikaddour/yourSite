<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldSpecialtyResource extends JsonResource
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
            'designationFr'   => $this->designation_fr,
            'designationAr'   => $this->designation_ar,
            'designationEn'   => $this->designation_en,
            'acronym'         => $this->acronym,
            'field'           => new FieldResource($this->whenLoaded('field')),
            'occupations'     => OccupationResource::collection($this->whenLoaded('occupations')),
        ];
    }
}
