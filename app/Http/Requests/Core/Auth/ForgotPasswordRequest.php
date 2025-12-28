<?php

namespace App\Http\Requests\Core\Auth;

use App\Http\Requests\ApiFormRequest;

class ForgotPasswordRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * All users are authorized since this request is used before authentication.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Rules:
     * - `email`: Must be present, valid format, and exist in the `users` table.
     * - `code`: Must be present and at most 6 characters (OTP).
     * - `password`: Must be present, at least 8 characters, and no more than 255.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
           'code' => ['required', 'digits:6'],
            'password' => ['required', 'min:8', 'max:255'],
        ];
    }
}
