<?php

namespace App\Http\Resources\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankingInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Return an array representation of the banking information resource
        return [
            'id'             => $this->id,                        // Unique identifier for this banking record
            'agencyAr'       => $this->agency_ar,                 // Agency name in Arabic
            'agencyFr'       => $this->agency_fr,                 // Agency name in French
            'agencyEn'       => $this->agency_en,                 // Agency name in English
            'agencyCode'     => $this->agency_code,               // Agency code
            'accountNumber'  => $this->account_number,            // Bank account number
            'bankId'         => $this->bank_id,                   // Foreign key to the bank
            'bankableId'     => $this->bankable_id,               // Polymorphic relation ID (e.g., user/establishment)
            'bankableType'   => $this->bankable_type,             // Polymorphic relation type (e.g., App\Models\User)
            'isActive'       => $this->is_active,                 // Boolean flag for active/inactive status
        ];
    }
}
