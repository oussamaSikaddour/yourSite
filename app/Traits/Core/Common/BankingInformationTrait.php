<?php

namespace App\Traits\Core\Common;

trait BankingInformationTrait
{
    /**
     * Validates a RIB (bank account number) by comparing the provided key with a calculated one.
     *
     * Format expected: first 3 digits are bank code, next 5 are agency code,
     * next 10 are account number, and last 2 are the control key.
     *
     * @param string $bankAccount The full RIB string (at least 20 digits).
     * @return array An array containing the validation status, expected key, and provided key.
     */
    public function checkBankAccount(string $bankAccount)
    {
        // Extract parts of the RIB
        $banqueCode = substr($bankAccount, 0, 3);        // Bank code (not used in calculation)
        $agencyCode = substr($bankAccount, 3, 5);        // Agency code
        $accountNumber = substr($bankAccount, 8, 10);    // Account number
        $providedKey = substr($bankAccount, 18, 2);      // Provided control key (last 2 digits)

        // Calculate expected key
        $r1 = $agencyCode . $accountNumber;              // Concatenate agency and account number
        $r2 = (int)$r1 * 100;                            // Multiply by 100
        $r3 = $r2 % 97;                                  // Modulo 97
        $ribKey = 97 - $r3;                              // Expected control key

        // Check if the provided key matches the calculated one
        $ribErrorTest = $ribKey == $providedKey;

        // Return result of validation
        return [
            "status"       => $ribErrorTest,
            "ribKey"       => $ribKey,
            "providedKey"  => $providedKey
        ];
    }
}
