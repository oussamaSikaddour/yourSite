<?php

namespace App\Rules\Core;

use App\Models\BankingInformation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AccountNumberExists implements ValidationRule
{
    /**
     * Constructor to initialize the bankable type and optionally an account number.
     *
     * @param string $bankableType - The class name that links to the model (e.g., 'App\Models\User', 'App\Models\Establishment').
     * @param string|null $accountNumber - The account number to check (optional).
     */
    public function __construct(
        private string $bankableType,
        private ?string $accountNumber = null
    ) {}

    /**
     * Run the validation rule.
     *
     * @param string $attribute - The attribute being validated.
     * @param mixed $value - The actual bankable ID being validated.
     * @param Closure $fail - Callback to fail validation with a message.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = BankingInformation::query()
            ->where('bankable_id', $value)
            ->where('bankable_type', $this->bankableType);

        if ($this->accountNumber !== null) {
            $query->where('account', $this->accountNumber);
        }


        if (!$query->exists()){
            $fail(__('rules.banking_information.account.not_exists'));
        }elseif($query->where('is_active', false)->exists()) {
            $fail(__('rules.banking_information.account.exists_not_active', [
                'account' => $this->accountNumber
            ]));
        }
}
}
