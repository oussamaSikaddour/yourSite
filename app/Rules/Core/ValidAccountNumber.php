<?php

namespace App\Rules\Core;

use App\Traits\Core\Common\BankingInformationTrait;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAccountNumber implements ValidationRule
{
    use BankingInformationTrait;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!(strlen($value) === 20 && ctype_digit($value))) {
          $fail(__('rules.banking_information.account.length'));
          return;
        }
      $ribCheck = $this->checkBankAccount($value);
        if (!$ribCheck["status"]) {
            $fail(__('rules.banking_information.account.check',
             [
                'providedKey' => $ribCheck["providedKey"],
                'ribKey' => $ribCheck["ribKey"]
              ]
            ));
        }
    }
}
