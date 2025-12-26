<?php

namespace App\Http\Requests\Core\Auth;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Validation\Rule;

class RegisterFirstStepRequest extends ApiFormRequest
{

    use ResponseTrait;
    /**
     * Anyone can register, so authorization is always true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the first registration step.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }


        public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('register', [
            "email",
            "password",
        ]);
    }
}
