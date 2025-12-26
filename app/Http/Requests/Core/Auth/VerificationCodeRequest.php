<?php

namespace App\Http\Requests\Core\Auth;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;

class VerificationCodeRequest extends ApiFormRequest
{

    use ResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * Authorization is allowed for all users (including guests).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Validation rules:
     * - `email`: must be present, formatted as a valid email, and exist in the `users` table.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users'],
        ];
    }

                public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('register', [
           "code"
        ]);
    }
}
