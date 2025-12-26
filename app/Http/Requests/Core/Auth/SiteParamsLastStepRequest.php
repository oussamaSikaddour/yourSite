<?php

namespace App\Http\Requests\Core\Auth;

use App\Http\Requests\ApiFormRequest;

class SiteParamsLastStepRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Only users with the 'super-admin-access' permission can proceed.
     */
    public function authorize(): bool
    {
        return $this->user()->can('super-admin-access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Validation rules:
     * - `maintenance`: required and must be a boolean (true or false).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'maintenance' => [
                'required',
                'boolean',
            ],
            // Additional site parameters can be validated here in the future
        ];
    }

                public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('site_parameters.steps.last', [
            "maintenance",
        ]);
    }
}
