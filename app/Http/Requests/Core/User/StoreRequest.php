<?php

namespace App\Http\Requests\Core\User;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Validation\Rule;

class StoreRequest extends ApiFormRequest
{
    use ResponseTrait;
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [

            // Required for creation
            'name' => ['required', 'string', 'min:3', 'max:100'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],

            'password' => ['required', 'string', 'min:8', 'max:255'],

            'image' => [
                'sometimes',
                'file',
                'mimes:jpeg,png,gif,ico,webp',
                'max:10000',
            ],
        ];
    }


            public function attributes(): array
    {

        return $this->returnTranslatedResponseAttributes('user', [
            'is_active',
            'name',
            'password',
            'email',
            'person_id',
            'avatar'
        ]);
    }
}
