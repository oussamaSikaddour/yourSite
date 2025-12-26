<?php

namespace App\Http\Requests\Core\Auth;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;

class ChangePasswordRequest extends ApiFormRequest
{

    use ResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * In this case, we assume the route is protected by authentication middleware,
     * so all authenticated users are authorized to use this form.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * This normalizes camelCase keys (from JSON) to snake_case,
     * ensuring consistent validation regardless of client naming conventions.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'old_pwd' => $this->input('oldPwd', $this->input('old_pwd')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Rules:
     * - `password`: Required, minimum 8 characters, max 255.
     * - `new_password`: Required, minimum 8 characters, max 255,
     *                   and must be different from the current password.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'old_pwd' => ['required', 'min:8', 'max:255'],
            'pwd' => ['required', 'min:8', 'max:255', 'different:pwd'],
        ];
    }


    public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('change_password', [
            "mail",
            "new_mail",
        ]);
    }
}
