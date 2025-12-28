<?php

namespace App\Livewire\Forms\Core\ForgotPassword;

use App\Events\Core\Auth\VerificationEmailEvent;
use App\Models\User;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FirstForm extends Form
{
    use ResponseTrait;
    public $email ='';





    // Livewire rules
    public function rules()
    {
        return [
            'email' => ['required', 'email', "exists:users,email"]
        ];
    }

    public function validationAttributes()
    {
        return [
            'email' =>__("forms.forgot_password.email")
            // Add more attribute names as needed
        ];
    }


    public function save()
    {
        try {
         $data = $this->validate();
         $user= User::where("email", $data['email'])->first();
         event(new VerificationEmailEvent($user));
         return $this->response(true,message:__("forms.forgot_password.responses.new_code"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        }
       catch (\Exception $e) {
        Log::error('ForgotPassword first form error: ' . $e->getMessage());
        return $this->response(false, errors: __('forms.common.errors.default'));
         }
}

}
