<?php

namespace App\Livewire\Forms\Core\User;

use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'name'  => ['nullable', 'string', 'min:3', 'max:100'],
            'email'    => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'avatar'   => ['nullable', 'file', 'mimes:jpeg,png,gif,ico,webp', 'max:10000'],
        ];
    }

    /**
     * Localized attribute names.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('user', [
            'avatar',
            'name',
            'password',
            'email'
        ]);
    }

    /**
     * Store the user inside a database transaction.
     */
    public function save($user)
    {
        try {
            $data = $this->validate();

            return DB::transaction(function () use ($user, $data) {

                // Create user
                if (!empty($data['password'])) {
                    $data['password'] = Hash::make($data['password']);
                } else {
                    unset($data['password']); // prevent null overwrite
                }

                $user->update($data);

                $this->uploadImage($user);

                return $this->response(
                    true,
                    message: __('forms.user.responses.update_success', ['name' => $user->name])
                );
            });
        } catch (\Illuminate\Validation\ValidationException $e) {

            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Throwable $e) {

            Log::error('UpdateForm User creation error: ' . $e->getMessage());

            return $this->response(
                false,
                errors: __('forms.common.errors.default')
            );
        }
    }

    /**
     * Upload user avatar.
     */
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
