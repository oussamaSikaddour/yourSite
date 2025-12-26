<?php

namespace App\Http\Requests\Core\Auth;

use App\Http\Requests\ApiFormRequest;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Validation\Rule;

class ChangeEmailRequest extends ApiFormRequest
{

    use ResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * In this case, all authenticated users are authorized.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Merge camelCase fields into snake_case before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'new_mail' => $this->newMail,
        ]);
    }



    /**
     * Define validation rules for changing the email address.
     *
     * - `password`: Must be the current authenticated password.
     * - `old_email`: Must match the current user's email.
     * - `new_email`: Must be a unique, different email address not soft-deleted.
     */
    public function rules(): array
    {
        return [
            'password' => 'required|current_password',

            'mail' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(function ($query) {
                    // Ensures the old email matches the authenticated user’s email
                    $query->where('id', auth()->id());
                }),
            ],

            'new_mail' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
                'different:mail', // Prevents reusing the same email
            ]
        ];
    }


    /**
     * Define custom validation messages.
     */
    public function messages(): array
    {
        return [
            'mail.exists' => __('api.change_email.errors.old_email'),
            'new_mail.different' => __('api.change_email.errors.new_email'),
        ];
    }


                public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('change_mail', ["mail","new_mail",
        ]);
    }
}
