<?php

namespace App\Http\Requests\Core\Auth;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class ManageRolesRequest extends FormRequest
{

    use ResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * Only users with 'super-admin-access' permission can proceed.
     */
    public function authorize(): bool
    {
        return $this->user()->can('super-admin-access');
    }



    /**
     * Prepare the data for validation.
     *
     * Maps `userId` input to `user_id` for internal consistency.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('userId')) {
            $this->merge(['user_id' => $this->userId]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'roles'   => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * Uses Laravel localization for super-localizable messages.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => __('forms.role.errors.user_id_required'),
            'user_id.exists'   => __('forms.role.errors.user_id_exists'),
            'roles.required'   => __('forms.role.errors.roles_required'),
            'roles.array'      => __('forms.role.errors.roles_array'),
            'roles.*.exists'   => __('forms.role.errors.roles_exist'),
        ];
    }


    public function attributes(): array
    {
        return $this->returnTranslatedResponseAttributes('role', [
            "user_id",
            "roles",
        ]);
    }
}
