<?php

namespace App\Livewire\Forms\App\Transfer;


use App\Models\BankTransfer;
use App\Models\User;
use App\Rules\AccountNumberExists;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $amount;
    public $user_id;
    public $global_bank_transfer_id;
    /**
     * Define validation rules.
     */
    public function rules()
    {



        return [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],
            'user_id' => ['required', 'exists:users,id',new AccountNumberExists(User::class)],
            'global_bank_transfer_id' => ['required', 'exists:global_bank_transfers,id'],
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('transfer', [
            'amount','user_id','global_bank_transfer_id'
        ]);
    }

    /**
     * Save the banking information.
     */
    public function save()
    {
        try {
            // Validate request data
            $data = $this->validate();
           BankTransfer::create($data);
            return $this->response(true, message: __("forms.transfer.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in GlobalTransfer AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
