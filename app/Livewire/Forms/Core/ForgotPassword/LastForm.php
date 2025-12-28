<?php

namespace App\Livewire\Forms\Core\ForgotPassword;

use App\Enum\Core\Web\RoutesNames;
use App\Models\User;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Otp;
class LastForm extends Form
{
    use ResponseTrait;
    public $code ='';
    public $email ="";
    public $password ="";

    // Livewire rules
    public function rules()
    {
        return [
            'email' => ['required', 'email', "exists:users,email"],
            'code' => ['required', 'max:6'],
            'password' =>  'required|min:8|max:255'
        ];
    }


    public function validationAttributes()
    {
        return [

            'email' => __('forms.forgot_password.email'),
            'password' =>__('forms.forgot_password.steps.last.password'),
            'code' => __('forms.forgot_password.steps.last.code')
            // Add more attribute names as needed
        ];
    }




    public function save()
    {

        // Validate the data
          // Validate the form data
          try {
            $data = $this->validate();

        // Attempt to find the user by email
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
                  throw new \Exception(__('forms.forgot_password.errors.no_user'));
        }

        // Create an instance of the Otp class
        $otp = new Otp();

        // Validate the OTP code for the provided email
        $validationResult = $otp->validate($data['email'], $data['code']);

            if ($validationResult->status) {
                   // Update the user's password
                   $user->update(['password' => Hash::make($data['password'])]);

                  // Authenticate the user
                   Auth::login($user);


                return $this->response(true, data: ['route' => RoutesNames::DASHBOARD->value]);

               } else {

                    throw new \Exception(__('forms.forgot_password.errors.verification_code'));
               }
            }
             catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        }catch (\Exception $e) {

            Log::error('ForgotPassword last form error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
          }
    }
}
