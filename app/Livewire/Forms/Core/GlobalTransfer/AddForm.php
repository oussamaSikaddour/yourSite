<?php

namespace App\Livewire\Forms\Core\GlobalTransfer;

use App\Livewire\Default\Admin\GlobalTransferModal;
use App\Models\GlobalBankTransfer;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait;

    public $number;
    public $date;
    public $motive_ar;
    public $motive_fr;
    public $motive_en;
    public $user_id;
    /**
     * Define validation rules.
     */
    public function rules()
    {

        $localizedMotiveRules = [
            'nullable', 'string', 'min:5', 'max:60',
            Rule::unique('global_bank_transfers')
                    ->whereNull('deleted_at')
        ];

        return [
            'number' => ['required', 'integer', 'min:1', 'unique:global_bank_transfers,number'], // Ensure the number is unique in the table
            'date' => ['required', 'date'], // Ensure the date is a valid date
            'motive_ar' =>$localizedMotiveRules,
            'motive_fr' =>$localizedMotiveRules,
            'motive_en' =>$localizedMotiveRules,
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Define attribute names for validation messages.
     */
    public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('global_transfers', [
            'number','date','motive_ar','motive_fr','motive_en','user_id'
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

            $latestTransfer = GlobalBankTransfer::whereDate('date', $data['date'])
            ->orderBy('reference', 'desc')
            ->first();

        // Set the reference number
        $data['reference'] = $latestTransfer ? $latestTransfer->reference + 1 : 1;
           GlobalBankTransfer::create($data);
            return $this->response(true, message: __("forms.global_transfer.responses.add_success"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Error in GlobalTransfer AddForm save method: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }
}
