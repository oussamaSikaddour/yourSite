<?php

namespace App\Livewire\Forms\Core\Role;

use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Form;

class ManageForm extends Form
{
    use ResponseTrait;

    public $roles = [];

    public function rules()
    {
        return [
            'roles'   => ['present', 'array'],          // allow []
            'roles.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'roles' => __('forms.role.roles'),
        ];
    }

    public function save($user)
    {
        try {
            $data = $this->validate();

            // Livewire often sends checkbox values as strings: ["1","2"]
            // Normalize to ints so strict in_array works correctly
            $data['roles'] = array_map('intval', $data['roles'] ?? []);

            return DB::transaction(function () use ($user, $data) {

                $superAdminRoleId = (int) Role::where('slug', 'super_admin')->value('id');

                // If role doesn't exist, just sync normally
                if (! $superAdminRoleId) {
                    $user->roles()->sync($data['roles']);
                    return $this->response(true, message: __('forms.role.responses.success'));
                }

                // Is this user currently super_admin?
                $userIsSuperAdmin = $user->roles()
                    ->where('roles.id', $superAdminRoleId)
                    ->exists();

                // Are we removing super_admin from them?
                $removingSuperAdmin = $userIsSuperAdmin
                    && ! in_array($superAdminRoleId, $data['roles'], true);

                if ($removingSuperAdmin) {
                    // Check if there is at least one OTHER super_admin user
                    $otherSuperAdminsCount = User::whereKeyNot($user->id)
                        ->whereHas('roles', function ($q) use ($superAdminRoleId) {
                            $q->where('roles.id', $superAdminRoleId);
                        })
                        ->count();

                    if ($otherSuperAdminsCount === 0) {
                        return $this->response(false, errors: [
                            __('forms.role.errors.unique_super_admin'),
                        ]);
                    }
                }

                // Sync roles
                $user->roles()->sync($data['roles']);

                return $this->response(true, message: __('forms.role.responses.success'));
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('error in manage roles form : ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
