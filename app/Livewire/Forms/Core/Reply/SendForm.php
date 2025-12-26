<?php

namespace App\Livewire\Forms\Core\Reply;

use App\Events\Default\ReplyEvent;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SendForm extends Form
{
    use ResponseTrait ;

    public $name;
    public $message;
    public $email;






    public function rules()
    {


        return [
            "message"=>'required|string|min:10'
        ];

    }



 public function validationAttributes()
 {
     return [
         'message' => __('forms.reply.message'),
     ];
 }


 public function save()
 {
        // Validate the form data
        try {
            $data = $this->validate();

         $data["name"]=$this->name;
         $data["email"]=$this->email;
        event(new ReplyEvent($data));
        return $this->response(true,message:__('modals.reply.success'));
     } catch (\Illuminate\Validation\ValidationException $e) {
        // Return all validation errors
        return $this->response(false, errors: $e->validator->errors()->all());
    }

     catch (\Exception $e) {
        Log::error('Login form error: ' . $e->getMessage());
        return $this->response(false, errors: __('forms.common.errors.default'));
     }
 }
}
