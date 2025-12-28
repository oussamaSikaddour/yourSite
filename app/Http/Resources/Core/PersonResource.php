<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'lastNameFr'     => $this->last_name_fr,      // Last name in French
            'firstNameFr'    => $this->first_name_fr,     // First name in French
            'lastNameAr'     => $this->last_name_ar,      // Last name in Arabic
            'firstNameAr'    => $this->first_name_ar,     // First name in Arabic

            'cardNumber'     => $this->card_number,       // National or identity card number

            'birthPlaceFr'   => $this->birth_place_fr,    // Birthplace in French
            'birthPlaceAr'   => $this->birth_place_ar,    // Birthplace in Arabic
            'birthPlaceEn'   => $this->birth_place_en,    // Birthplace in English

            'birthDate'      => $this->birth_date,        // Date of birth

            'addressFr'      => $this->address_fr,        // Address in French
            'addressAr'      => $this->address_ar,        // Address in Arabic
            'addressEn'      => $this->address_en,        // Address in English

            'phone'          => $this->phone,             // Phone number
            'isPaid' => (bool) $this->is_paid,
            'user' => new UserResource($this->whenLoaded('user')),
            'occupations' => OccupationResource::collection($this->whenLoaded('occupations')),
            'bankingInformation' => BankingInformationResource::collection($this->whenLoaded('bankingInformation')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
