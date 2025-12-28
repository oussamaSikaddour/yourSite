<?php

namespace App\Http\Requests\Core\Auth;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;

class LoginRequest extends ApiFormRequest
{

    use ResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * All users are authorized to attempt login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Rules:
     * - `email`: required, must be in email format, and must exist in the `users` table.
     * - `password`: required, with a length between 8 and 255 characters.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['bail','required', 'email'],
            'password' => ['required', 'min:8', 'max:255'],
        ];
    }

            public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('login', ["email","password",
        ]);
    }
}
