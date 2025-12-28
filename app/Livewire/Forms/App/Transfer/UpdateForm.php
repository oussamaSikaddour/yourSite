<?php

namespace App\Livewire\Forms\App\Transfer;

use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait;

    public $id;
    public $amount;
    /**
     * Define validation rules.
     */
    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'amount' => __("modals.transfer.amount"),
        ];
    }

    /**
     * Save the banking information.
     */
    public function save($transfer)
    {
        try {
            // Validate request data
            $data = $this->validate();
         $transfer->update($data);
            return $this->response(true, message: __("forms.transfer.responses.update_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in GlobalTransfer AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
