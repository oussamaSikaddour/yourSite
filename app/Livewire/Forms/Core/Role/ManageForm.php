<?php

namespace App\Livewire\Forms\Core\Role;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ManageForm extends Form
{
    use ResponseTrait;
public $roles =[];

    public function rules()
    {
        return [
            'roles'   => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'roles' => 'les privileges'
            // Add more attribute names as needed
        ];
    }


    public function save($user)
    {
              // Validate the form data
              try {
                $data = $this->validate();
            $user->roles()->sync($data['roles']);
            return $this->response(true,message:(__('forms.role.responses.success')));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return all validation errors
            return $this->response(false, errors: $e->validator->errors()->all());
        }catch (\Exception $e) {
            Log::error('error in manage roles form : ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
