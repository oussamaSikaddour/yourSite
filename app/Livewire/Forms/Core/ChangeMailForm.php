<?php
namespace App\Livewire\Forms\Core;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ChangeMailForm extends Form
{
    use ResponseTrait;

    public $newEmail = '';
    public $oldEmail = '';
    public $password = '';

    public function rules()
    {
        return [

            'password' => 'required|min:8|max:255',
            'oldEmail' => ['required', 'email', "exists:users,email"],
            'newEmail' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
                'different:oldEmail',
            ],
        ];
    }

    public function validationAttributes()
    {
        return [
            'oldEmail' => __("forms.change_mail.old-email"),
            'newEmail' => __("forms.change_mail.new-email"),
            'password' => __("forms.change_mail.pwd"),
        ];
    }



 public function save()
    {
        try {
            $data = $this->validate();

            if (Auth::attempt(['email' => $data['oldEmail'], 'password' => $data['password']])) {
                $user = Auth::user();
                $user->email = $data['newEmail'];
                $user->save();
                return $this->response(true, message: __("forms.change_mail.responses.success"));
            } else {
                throw new \Exception(__('forms.change_mail.errors.auth'));
            }
        }catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
} catch (\Exception $e) {
            Log::error('ChangeMail form error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
