<?php

namespace App\Livewire\Forms\Core\SiteParameters;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FirstStepForm extends Form
{
    use ResponseTrait;
    public $email ="";
    public $password ="";

    // Livewire rules
    public function rules()
    {
        return [
            'email' => ['required', 'email', "exists:users,email"],
            'password' =>  'required|min:8|max:255'
        ];
    }


    public function validationAttributes()
    {
        return [

            'email' =>__('forms.site_parameters.steps.first.email'),
            'password' => __('forms.site_parameters.steps.first.password'),
            // Add more attribute names as needed
        ];
    }




    public function save()
    {
        // // Validate the data
          // Validate the form data
          try {
            $data = $this->validate();


                if (Auth::attempt($data)) {
                  $user = Auth::user();
                   $userIsSuperAdmin= $user->roles->contains('slug', 'super_admin');
                 if(!$userIsSuperAdmin){
                    throw new \Exception(__('forms.site_parameters.errors.no_access'));
                 }else{

                    return $this->response(true,message:
                                               __('forms.site_parameters.responses.you_can_pass'));
                   }
                 } else {
                   throw new \Exception(__('forms.site_parameters.errors.user_not_found'));
                   }

        } catch (\Illuminate\Validation\ValidationException $e) {
                    return $this->response(false, errors: $e->validator->errors()->all());
       }
        catch (\Exception $e) {

            Log::error('SiteParameters first form error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }

}
