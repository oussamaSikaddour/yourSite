<?php

namespace App\Livewire\Forms\Core;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ChangePasswordForm extends Form
{
    use ResponseTrait;

    public $newPassword = "";
    public $password = "";

    public function rules()
    {
        return [
            'password' => 'required|min:8|max:255',
            'newPassword' => 'required|min:8|max:255|different:password',
        ];
    }

    public function validationAttributes()
    {
        return [
            'password' => __("forms.change_password.old_pwd"),
            'newPassword' => __("forms.change_password.pwd"),
        ];
    }

    public function save()
    {
        try {
            $data = $this->validate();
            $user = Auth::user();
            if (!Auth::attempt(['email' => $user->email, 'password' => $data['password']])) {
                throw new \Exception(__("forms.change_password.errors.old_pwd"));
            }
            $user["password"] = Hash::make($data['newPassword']);
            $user->update();
            return $this->response(true, message: __("forms.change_password.responses.success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
}catch (\Exception $e) {
            Log::error('ChangePassword form error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
