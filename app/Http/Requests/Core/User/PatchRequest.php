<?php

namespace App\Http\Requests\Core\User;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;

class PatchRequest extends ApiFormRequest
{

    use ResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_active' => ['sometimes', 'boolean'],

            'avatar' => [
                'sometimes',
                'file',
                'mimes:jpeg,png,gif,ico,webp',
                'max:10000'
            ],


            'name' => ['sometimes', 'string', 'min:3', 'max:100'],

            'password' => ['sometimes', 'string', 'min:8', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Only useful if you need to adjust field names
        if ($this->has('isActive')) {
            $this->merge([
                'is_active' => $this->isActive,
            ]);
        }
    }


        public function attributes(): array
    {

        return $this->returnTranslatedResponseAttributes('user', [
            'is_active',
            'name',
            'password',
            'email',
            'avatar'
        ]);
    }
}
