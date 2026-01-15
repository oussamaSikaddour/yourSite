<?php

namespace App\Livewire\Forms\Core\User;

use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait, ModelImageTrait, ModelFileTrait;

    public $id;
    public $name;
    public $email;
    public $password;
    public $avatar;
    public $is_active;

    public function rules(): array
    {
        return [
            'name'     => ['nullable', 'string', 'min:3', 'max:100'],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'avatar'   => ['nullable', 'file', 'mimes:jpeg,png,gif,ico,webp', 'max:10000'],
            'is_active'=> ['required', 'boolean'],
        ];
    }

    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('user', [
            'avatar',
            'name',
            'password',
            'email',
            'is_active',
        ]);
    }

    public function save($user)
    {
        try {
            $data = $this->validate();

            // Normalize Livewire boolean values ("0"/"1"/0/1/true/false) to real bool
            $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $data['is_active'] = $data['is_active'] ?? false;

            return DB::transaction(function () use ($user, $data) {

                // ---- SUPER ADMIN "last active" protection ----
                $superAdminRoleId = (int) Role::where('slug', 'super_admin')->value('id');

                if ($superAdminRoleId) {
                    $userIsSuperAdmin = $user->roles()
                        ->where('roles.id', $superAdminRoleId)
                        ->exists();

                    $deactivating = $userIsSuperAdmin
                        && (bool) $user->is_active === true
                        && (bool) $data['is_active'] === false;

                    if ($deactivating) {
                        $otherActiveSuperAdmins = User::whereKeyNot($user->id)
                            ->where('is_active', true)
                            ->whereHas('roles', function ($q) use ($superAdminRoleId) {
                                $q->where('roles.id', $superAdminRoleId);
                            })
                            ->count();

                        if ($otherActiveSuperAdmins === 0) {
                            return $this->response(false, errors: [
                                __('forms.user.errors.unique_super_admin'),
                                // or reuse: __('forms.role.errors.unique_super_admin')
                            ]);
                        }
                    }
                }
                // --------------------------------------------

                // Password handling
                if (! empty($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                } else {
                    unset($data['password']); // prevent null overwrite
                }

                // Update user
                $user->update($data);

                // Upload avatar (if provided)
                $this->uploadImage($user);

                return $this->response(
                    true,
                    message: __('forms.user.responses.update_success', ['name' => $user->name])
                );
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Throwable $e) {
            Log::error('UpdateForm User update error: ' . $e->getMessage());

            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }

    protected function uploadImage(User $user): void
    {
        if ($this->avatar) {
            $this->uploadAndUpdateImage(
                $this->avatar,
                $user->id,
                User::class,
                'avatar'
            );
        }
    }
}


